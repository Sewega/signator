<?php

declare(strict_types=1);

namespace Sewega\Signator;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sewega\Signator\Skeleton\SkeletonClass
 */
class SignatorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'signator';
    }
}
