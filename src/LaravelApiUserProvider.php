<?php

namespace Amin3536\LaravelApiUserProvider;

use App\Modules\authService\CustomTokenGuard;
use App\Modules\authService\ExternalUserProvider;
use App\Modules\interactModule\GuzzelHttpClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class LaravelApiUserProvider extends ServiceProvider
{
    public function boot()
    {
//       TODO: manage all service client  (auth , leitner   with container later   :)
//        $this->app->bind(HttpClient::class, function ($app) {
//            return $app->make(new GuzzelHttpClient());
//        });

        Auth::provider('api-provider', function ($app, array $config) {
            return new ExternalUserProvider(new GuzzelHttpClient(), $config['model'],$config['url']);
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
    }


    /**
     * Get the user provider configuration.
     *
     * @param  string|null $provider
     * @return array|null
     */
    protected function getProviderConfiguration($provider)
    {
        if ($provider = $provider ?: $this->getDefaultUserProvider()) {
            return $this->app['config']['auth.providers.' . $provider];
        }
    }
}
