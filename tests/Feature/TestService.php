<?php


use Amin3536\LaravelApiUserProvider\authService\DeserializerInterface;

class TestService extends \Tests\TestCase
{

    public function testProvider()
    {
        $this->app->resolved(DeserializerInterface::class, function (DeserializerInterface $deserializer) {
            dump($deserializer);
        });

    }

}
