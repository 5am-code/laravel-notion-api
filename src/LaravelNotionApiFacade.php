<?php

namespace FiveamCode\LaravelNotionApi;

use Illuminate\Support\Facades\Facade;

/**
 * @see \FiveamCode\LaravelNotionApi\Skeleton\SkeletonClass
 */
class LaravelNotionApiFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-notion-api';
    }
}
