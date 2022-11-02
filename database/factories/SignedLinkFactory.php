<?php

declare(strict_types = 1);

namespace Sewega\Signator\Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Sewega\Signator\Enums\SignedLinkMethod;
use Sewega\Signator\SignedLink;

class SignedLinkFactory extends Factory
{

    /**
     * @var string
     */
    protected $model = SignedLink::class;

    /**
     * @return array<mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid()->toString(),
            'route' => 'login',
            'method' => SignedLinkMethod::GET->value,
            'usage_limit' => null,
            'user_id' => $this->faker->randomDigit(),
            'available_from' => Carbon::now(),
            'available_until' => null,
        ];
    }

}
