<?php

use Illuminate\Foundation\Testing\TestCase;

/**
 * Created by PhpStorm.
 * User: amin
 * Date: 2/21/21
 * Time: 1:19 PM
 */

class TestProvider extends TestCase
{


//    protected function setUp()
//    {
//        parent::setUp();
//
//        app()->bind(YourService::class, function() { // not a service provider but the target of service provider
//            return new YourFakeService();
//        });
//    }


    /**
     * Creates the application.
     *
     * Needs to be implemented by subclasses.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        // TODO: Implement createApplication() method.
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
}
