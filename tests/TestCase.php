<?php

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders()
    {
        return [
            Amin3536\LaravelApiUserProvider\LaravelApiUserProviderServiceProvider::class,
        ];
    }
}
