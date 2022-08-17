<?php

namespace Amin3536\LaravelApiUserProvider\Tests\Feature;

use Amin3536\LaravelApiUserProvider\authService\ExternalUserProvider;
use Amin3536\LaravelApiUserProvider\interactModule\HttpClient;
use Amin3536\LaravelApiUserProvider\Tests\TestCase;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Mockery\MockInterface;

class ApiUserProviderGuardTest extends TestCase
{
    /**
     * Test unauthorized request.
     *
     * @return void
     */
    public function test_unauthorized_request()
    {
        $response = $this->getJson(route('user'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Test handle auth server return server error(500).
     *
     * @return void
     */
    public function test_auth_server_return_server_error()
    {
        $mockHttpClient = $this->partialMock(HttpClient::class, function (MockInterface $mock) {
            $mock->shouldReceive('createRequest')->andReturnSelf();

            $mock->shouldReceive('sendRequest')
                ->andReturn(new Response([], Response::HTTP_INTERNAL_SERVER_ERROR));
        });

        $mockUserProvider = $this->partialMock(ExternalUserProvider::class, function (MockInterface $mock) {
                $mock->shouldReceive('deserializerContent')->andReturn(new User());
        });

        $this->app->instance(HttpClient::class, $mockHttpClient);
        $this->app->instance(ExternalUserProvider::class, $mockUserProvider);

        $response = $this->getJson(route('user'), [
            'Authorization' => 'Bearer ' . Str::random(64),
        ]);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Test handle auth server return unauthorized token(401).
     *
     * @return void
     */
    public function test_auth_server_return_unauthorized_token()
    {
        $mockHttpClient = $this->partialMock(HttpClient::class, function (MockInterface $mock) {
            $mock->shouldReceive('createRequest')->andReturnSelf();

            $mock->shouldReceive('sendRequest')
                ->andReturn(new Response([], Response::HTTP_UNAUTHORIZED));
        });

        $mockUserProvider = $this->partialMock(ExternalUserProvider::class, function (MockInterface $mock) {
            $mock->shouldReceive('deserializerContent')->andReturn(new User());
        });

        $this->app->instance(HttpClient::class, $mockHttpClient);
        $this->app->instance(ExternalUserProvider::class, $mockUserProvider);

        $response = $this->getJson(route('user'), [
            'Authorization' => 'Bearer ' . Str::random(64),
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }


    /**
     * Test send bearer token and get user successfully.
     *
     * @return void
     */
    public function test_get_auth_user_successfully()
    {
        $userData = [
            'id' => 1,
            'email' => 'test@gmail.com',
        ];
        $mockHttpClient = $this->partialMock(HttpClient::class, function (MockInterface $mock) use ($userData) {
            $mock->shouldReceive('createRequest')->andReturnSelf();

            $mock->shouldReceive('sendRequest')
                ->andReturn(new Response($userData, Response::HTTP_OK));
        });

        $mockUserProvider = $this->partialMock(
            ExternalUserProvider::class,
            function (MockInterface $mock) use ($userData) {
                $mock->shouldReceive('deserializerContent')
                    ->andReturn(new User());
            }
        );

        $this->app->instance(HttpClient::class, $mockHttpClient);
        $this->app->instance(ExternalUserProvider::class, $mockUserProvider);

        $response = $this->getJson(route('user'), [
            'Authorization' => 'Bearer ' . Str::random(64),
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'id',
                'name',
            ])->assertExactJson([
                'id' => 1,
                'name' => 'john doe',
            ]);

        $this->assertNotNull(Auth::user());
    }
}
