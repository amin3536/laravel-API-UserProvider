<?php

namespace Amin3536\LaravelApiUserProvider;

use Amin3536\LaravelApiUserProvider\authService\CustomTokenGuard;
use Amin3536\LaravelApiUserProvider\authService\Deserializer;
use Amin3536\LaravelApiUserProvider\authService\DeserializerInterface;
use Amin3536\LaravelApiUserProvider\authService\ExternalUserProvider;
use Amin3536\LaravelApiUserProvider\interactModule\GuzzleHttpClient;
use Amin3536\LaravelApiUserProvider\interactModule\HttpClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class LaravelApiUserProviderServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(HttpClient::class, function () {
            return new GuzzleHttpClient($this->getBaseUrl(), $this->getTimeoutRequestToAuthServer());
        });
        $this->app->bind(DeserializerInterface::class, function () {
            return new Deserializer();
        });

        Auth::provider('api-provider', function ($app, array $config) {
            return $app->makeWith(ExternalUserProvider::class, ['model' => $config['model'], 'url' => $config['url']]);
        });

        Auth::Extend('api-token', function ($app, $name, array $config) { // @codingStandardsIgnoreLine
            $guard = new CustomTokenGuard(
                Auth::createUserProvider($config['provider']),
                $app['request'],
                $config['input_key'] ?? 'api_token',
                $config['storage_key'] ?? 'api_token',
                $config['hash'] ?? false
            );
            $this->app->refresh('request', $guard, 'setRequest');

            return $guard;
        });
    }

    /**
     * Get the user provider configuration.
     *
     * @return string|null
     */
    protected function getBaseUrl()
    {
        return $this->app['config']['auth.base-url'];
    }

    /**
     * Get the user provider configuration.
     *
     * @return string|null
     */
    protected function getTimeoutRequestToAuthServer()
    {
        return $this->app['config']->get('auth.TimeoutForRequestAuthServer', 2);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laravel-api-user-provider'];
    }
}
