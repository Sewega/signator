<?php

declare(strict_types=1);

namespace Sewega\Signator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Sewega\Signator\Enums\SignedLinkMethod;
use Sewega\Signator\Exceptions\InvalidRouteName;
use Carbon\Carbon;
use Illuminate\Routing\ImplicitRouteBinding;
use Illuminate\Support\Str;

class Signator
{


    /**
     * @param array<string|int> $routeParameters
     * @throws \Sewega\Signator\Exceptions\InvalidRouteName
     */
    public function createSignedLink(
        string $route,
        SignedLinkMethod $signedLinkMethod,
        int $userId,
        ?int $seconds = null,
        ?int $usageCount = null,
        array $routeParameters = []
    ): SignedLink
    {
        throw_if(!Route::has($route), InvalidRouteName::class);

        $availableFrom = Carbon::now();
        $availableUntil = $seconds
            ? $availableFrom->copy()->addSeconds($seconds)
            : null;

        return SignedLink::create([
            'uuid' => Str::uuid()->toString(),
            'route' => $route,
            'method' => $signedLinkMethod->value,
            'usage_limit' => $usageCount,
            'user_id' => $userId,
            'available_from' => $availableFrom,
            'available_until' => $availableUntil,
            'route_parameters' => json_encode($routeParameters),
        ]);
    }

    /**
     * @param array<string|int> $routeParameters
     */
    public function createSignedLinkForNSeconds(string $route, SignedLinkMethod $method, int $userId, array $routeParameters, int $seconds): SignedLink
    {
        return $this->createSignedLink($route, $method, $userId, $seconds, null, $routeParameters);
    }

    /**
     * @param array<string|int> $routeParameters
     * @throws \Sewega\Signator\Exceptions\InvalidRouteName
     */
    public function createSignedLinkForOneUse(string $route, SignedLinkMethod $method, int $userId, array $routeParameters = []): SignedLink
    {
        return $this->createSignedLink($route, $method, $userId, null, 1, $routeParameters);
    }

    public function requestHasValidMethod(SignedLink $signedLink, Request $request): bool
    {
        return strtolower($request->getMethod()) === strtolower($signedLink->method->value);
    }

    public function signedLinkIsAvailableByDate(SignedLink $signedLink): bool
    {
        return $signedLink->available_from->isBefore(Carbon::now())
            && (
                $signedLink->available_until === null
                || $signedLink->available_until->isAfter(Carbon::now())
            );
    }

    public function signedLinkIsAvailableByUsage(SignedLink $signedLink): bool
    {
        return $signedLink->usage_limit === null || $signedLink->usage_limit > $signedLink->used;
    }

    public function signedLinkRouteIsValid(SignedLink $validRouteLink): bool
    {
        return Route::has($validRouteLink->route);
    }

    public function canBeUsed(SignedLink $signedLink, Request $request): bool
    {
        return $this->requestHasValidMethod($signedLink, $request)
            && $this->signedLinkIsAvailableByDate($signedLink)
            && $this->signedLinkIsAvailableByUsage($signedLink)
            && $this->signedLinkRouteIsValid($signedLink);
    }

    /**
     * TODO: tests
     */
    public function handleRequest(SignedLink $signedLink, Request $request): mixed
    {
        abort_if(!$this->canBeUsed($signedLink, $request), 404);

        $parameters = [];

        foreach ($signedLink->route_parameters as $key => $routeParameter) {
            $parameters[$key] = $routeParameter;
        }

        $request = Request::create(route($signedLink->route, $parameters), $signedLink->method->value, [], [], [], [], null);
        $route = Route::getRoutes()->match($request);
        ImplicitRouteBinding::resolveForRoute(app(), $route);

        /** @var \Illuminate\Auth\SessionGuard $sessionGuard */
        $sessionGuard = app()['auth']->guard(null);
        $sessionGuard->onceUsingId($signedLink->user_id);

        $signedLink->update(['used' => $signedLink->used + 1]);

        return app()->handle($request);
    }

}
