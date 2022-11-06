<?php

declare(strict_types = 1);

namespace Sewega\Signator;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sewega\Signator\Enums\SignedLinkMethod;

/**
 * App\Topol\Helpers\Signator\SignedLink
 *
 * @property string $uuid
 * @property string $route
 * @property SignedLinkMethod $method
 * @property array<int|string> $routeParameters
 * @property array<int|string> $route_parameters
 * @property int $used
 * @property int|null $usage_limit
 * @property int $user_id
 * @property int $team_id
 * @property \Carbon\Carbon $available_from
 * @property \Carbon\Carbon|null $available_until
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $url
 * @method static \Sewega\Signator\Database\Factories\SignedLinkFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|\Sewega\Signator\SignedLink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Sewega\Signator\SignedLink newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Sewega\Signator\SignedLink query()
 * @method static self create(array $data = [])
 * @property int $id
 * @method static \Illuminate\Database\Eloquent\Builder|\Sewega\Signator\SignedLink whereAvailableFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Sewega\Signator\SignedLink whereAvailableUntil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Sewega\Signator\SignedLink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Sewega\Signator\SignedLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Sewega\Signator\SignedLink whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Sewega\Signator\SignedLink whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Sewega\Signator\SignedLink whereRouteParameters($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Sewega\Signator\SignedLink whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Sewega\Signator\SignedLink whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Sewega\Signator\SignedLink whereUsageLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Sewega\Signator\SignedLink whereUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Sewega\Signator\SignedLink whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Sewega\Signator\SignedLink whereUuid($value)
 * @mixin \Eloquent
 */
class SignedLink extends Model
{

    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'signed_links';

    /**
     * @var array<string>
     */
    protected $fillable = [
        'uuid',
        'route',
        'method',
        'route_parameters',
        'used',
        'usage_limit',
        'user_id',
        'available_from',
        'available_until',
    ];

    /**
     * @var array<string>
     */
    protected $dates = [
        'available_from',
        'available_until',
        'created_at',
        'updated_at',
    ];

    /**
     * @return array<string|int>
     */
    public function getRouteParametersAttribute(?string $routeParameters): array
    {
        return $routeParameters ? json_decode($routeParameters, true) : [];
    }

    public function getMethodAttribute($method): SignedLinkMethod
    {
        return SignedLinkMethod::from($method);
    }

    public function setMethodAttribute(string|SignedLinkMethod $method): void
    {
        $this->attributes['method'] = $method instanceof SignedLinkMethod
            ? $method->value
            : $method;
    }

    public function getUrlAttribute(): string
    {
        switch ($this->method) {
            case SignedLinkMethod::GET:
                return route('signator.signed-links.index', [
                    'signedLink' => $this->uuid,
                ]);

            case SignedLinkMethod::POST:
                return route('signator.signed-links.store', [
                    'signedLink' => $this->uuid,
                ]);

            case SignedLinkMethod::PUT:
                return route('signator.signed-links.update', [
                    'signedLink' => $this->uuid,
                ]);

            case SignedLinkMethod::DELETE:
                return route('signator.signed-links.delete', [
                    'signedLink' => $this->uuid,
                ]);

            default:
                // TODO handle
                return '';
        }

    }

}
