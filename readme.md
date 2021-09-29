# LaravelApiUserProvider

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

this pack help you to provide your user from different service ( useful in micro service )
### how this package  work
this package use  bearer token  in header's  request and provide user from Sec Service (Auth service) 

## Installation

Via Composer

``` bash
$ composer require amin3536/laravel-api-user-provider
```


## Usage
simple usage  this driver  : change your    ``auth.php `` in ``config`` like below

```php
<?php

return [
    
        //.......
    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'api-token',
            'provider' => 'users',
        ],

        'admin-api' => [
            'driver' => 'api-token',
            'provider' => 'admins',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'api-provider',
            'model' => App\Models\User::class,
           //merged with base_url or you can use full path api here  =>http://localhost/api/admin/
            'url' => '/api/v1/user/'
        ],
        'admins' => [
            'driver' => 'api-provider',
            'model' => App\Models\Admin::class,
            'url' => '/api/v1/admin/'
        ],
    ],
     /*
        |--------------------------------------------------------------------------
        | Base url path to to call Auth Service 
        |--------------------------------------------------------------------------
        |
        */
        'base-url'=>'localhost',
        'TimeoutForRequestAuthServer'=>2
    //.......
];
```

## advance 
if you want  use custom Deserializer class    ,the class must be implement ``DeserializerInterface``  and add below code in boot method of AuthServiceProvider .
```php
<?php
    //...
    public function boot()
    {

        $this->app->when(ExternalUserProvider::class)
            ->needs(DeserializerInterface::class)
            ->give(function () {
                return new JsonToModel();
            });
    }
    
```


## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [amin3536][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/amin3536/laravel-api-user-provider.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/amin3536/laravel-api-user-provider.svg?style=flat-square
[ico-travis]: https://travis-ci.com/amin3536/laravel-API-UserProvider.svg?branch=master
[ico-styleci]: https://github.styleci.io/repos/339999725/shield

[link-packagist]: https://packagist.org/packages/amin3536/laravel-api-user-provider 
[link-downloads]: https://packagist.org/packages/amin3536/laravel-api-user-provider
[link-travis]: https://travis-ci.com/github/amin3536/laravel-API-UserProvider
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/amin3536
[link-contributors]: ../../contributors
