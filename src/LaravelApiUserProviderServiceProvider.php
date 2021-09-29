<?php

namespace Amin3536\LaravelApiUserProvider;

use Amin3536\LaravelApiUserProvider\authService\CustomTokenGuard;
use Amin3536\LaravelApiUserProvider\authService\Deserializer;
use Amin3536\LaravelApiUserProvider\authService\DeserializerInterface;
use Amin3536\LaravelApiUserProvider\authService\ExternalUserProvider;
use Amin3536\LaravelApiUserProvider\interactModule\GuzzelHttpClient;
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
//    public function boot(): void
//    {
//        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'amin3536');
//        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'amin3536');
//        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
//        // $this->loadRoutesFrom(__DIR__.'/routes.php');
//
//        // Publishing is only necessary when using the CLI.
//
//    }

    public function boot()
    {
        $this->app->bind(HttpClient::class, function ($app) {
            return $app->makeWith(GuzzelHttpClient::class, ['baseUrl' => $this->getBaseUrl(), 'timeout'=>$this->getTimeoutRequestToAuthServer()]);
        });
        $this->app->bind(DeserializerInterface::class, function ($app) {
            return $app->make(Deserializer::class);
        });

        Auth::provider('api-provider', function ($app, array $config) {
            return $app->makeWith(ExternalUserProvider::class, ['model' => $config['model'], 'url' => $config['url']]);
        });

        Auth::Extend('api-token', function ($app, $name, array $config) {
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

//        if ($this->app->runningInConsole()) {
//            $this->bootForConsole();
//        }
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

    protected function getTimeoutRequestToAuthServer()
    {
        return $this->app['config']->get('auth.TimeoutForRequestAuthServer',2);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
//        $this->mergeConfigFrom(__DIR__.'/../config/laravel-api-user-provider.php', 'laravel-api-user-provider');

//        // Register the service the package provides.
//        $this->app->singleton('laravel-api-user-provider', function ($app) {
//            return new LaravelApiUserProvider;
//        });
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

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
//        $this->publishes([
//            __DIR__.'/../config/laravel-api-user-provider.php' => config_path('laravel-api-user-provider.php'),
//        ], 'laravel-api-user-provider.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/amin3536'),
        ], 'laravel-api-user-provider.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/amin3536'),
        ], 'laravel-api-user-provider.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/amin3536'),
        ], 'laravel-api-user-provider.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
