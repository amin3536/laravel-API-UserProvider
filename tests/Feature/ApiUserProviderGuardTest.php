<?php

namespace Amin3536\LaravelApiUserProvider\Tests\Feature;

use Amin3536\LaravelApiUserProvider\interactModule\HttpClient;
use Amin3536\LaravelApiUserProvider\Tests\TestCase;
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
        // Act
        $response = $this->getJson(route('user'));

        // Assert
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Test handle auth server return server error(500).
     *
     * @return void
     */
    public function test_auth_server_return_server_error()
    {
        // Arrange
        $mockHttpClient = $this->partialMock(HttpClient::class, function (MockInterface $mock) {
            $mock->shouldReceive('createRequest')->andReturnSelf();

            $mock->shouldReceive('sendRequest')
                ->andReturn(new \GuzzleHttp\Psr7\Response(Response::HTTP_INTERNAL_SERVER_ERROR));
        });
        $this->app->instance(HttpClient::class, $mockHttpClient);

        // Act
        $response = $this->getJson(route('user'), [
            'Authorization' => 'Bearer ' . Str::random(64),
        ]);

        // Assert
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Test handle auth server return unauthorized token(401).
     *
     * @return void
     */
    public function test_auth_server_return_unauthorized_token()
    {
        // Arrange
        $mockHttpClient = $this->partialMock(HttpClient::class, function (MockInterface $mock) {
            $mock->shouldReceive('createRequest')->andReturnSelf();

            $mock->shouldReceive('sendRequest')
                ->andReturn(new \GuzzleHttp\Psr7\Response(Response::HTTP_UNAUTHORIZED));
        });
        $this->app->instance(HttpClient::class, $mockHttpClient);

        // Act
        $response = $this->getJson(route('user'), [
            'Authorization' => 'Bearer ' . Str::random(64),
        ]);

        // Assert
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }


    /**
     * Test send bearer token and get user successfully.
     *
     * @return void
     */
    public function test_get_auth_user_successfully()
    {
        // Arrange
        $userData = json_encode([
            'id' => 1,
            'name' => 'john doe',
        ]);

        $mockHttpClient = $this->partialMock(HttpClient::class, function (MockInterface $mock) use ($userData) {
            $mock->shouldReceive('createRequest')->andReturnSelf();

            $mock->shouldReceive('sendRequest')
                ->andReturn(new \GuzzleHttp\Psr7\Response(Response::HTTP_OK, [], $userData));
        });
        $this->app->instance(HttpClient::class, $mockHttpClient);

        // Act
        $response = $this->getJson(route('user'), [
            'Authorization' => 'Bearer ' . Str::random(64),
        ]);

        // Assert
        $response->assertOk()->assertExactJson(json_decode($userData, true));
        $this->assertNotNull(Auth::user());
    }
}
