<?php

namespace Amin3536\LaravelApiUserProvider\Tests;

use Amin3536\LaravelApiUserProvider\LaravelApiUserProviderServiceProvider;
use Illuminate\Foundation\Auth\User;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @return string[]
     */
    protected function getPackageProviders($app)  // @codingStandardsIgnoreLine
    {
        return [
            LaravelApiUserProviderServiceProvider::class,
        ];
    }

    /**
     * Get environment setup function.
     *
     * @param $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('auth.guards.api', [
            'driver' => 'api-token',
            'provider' => 'users',
        ]);

        $app['config']->set('auth.providers.users', [
            'driver' => 'api-provider',
            'model' => User::class,
            'url' => '/api/v1/user/',
        ]);

        $app['config']->set('auth.defaults.guard', 'api');
    }

    /**
     * Define routes setup.
     *
     * @param $router
     * @return void
     */
    protected function defineRoutes($router)
    {
        $router->get('user', function () {
            return response()->json([
                'id' => 1,
                'name' => 'john doe',
            ]);
        })->middleware('auth:api')->name('user');
    }
}
