<?php

namespace Amin3536\LaravelApiUserProvider\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelApiUserProvider extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-api-user-provider';
    }
}
