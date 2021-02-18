<?php

namespace Amin3536\LaravelApiUserProvider;

use Illuminate\Support\ServiceProvider;

class LaravelApiUserProviderServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'amin3536');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'amin3536');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-api-user-provider.php', 'laravel-api-user-provider');

        // Register the service the package provides.
        $this->app->singleton('laravel-api-user-provider', function ($app) {
            return new LaravelApiUserProvider;
        });
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
        $this->publishes([
            __DIR__.'/../config/laravel-api-user-provider.php' => config_path('laravel-api-user-provider.php'),
        ], 'laravel-api-user-provider.config');

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
