<?php

namespace Amin3536\LaravelApiUserProvider\authService;

use Amin3536\LaravelApiUserProvider\interactModule\HttpClient;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExternalUserProvider implements UserProvider
{
    /**
     * Http client variable
     *
     * @var HttpClient
     */
    public $httpClient;

    /**
     * The Eloquent user model variable
     *
     * @var string
     */
    protected $model;

    /**
     * The $url api service variable
     *
     * @var string
     */
    protected $url;

    /**
     * Deserializer variable
     *
     * @var mixed
     */
    public $deserializer;

    /**
     * ExternalUserProvider constructor.
     *
     * @param HttpClient $httpClient
     * @param $model
     * @param $url
     * @param DeserializerInterface|null $deserializer
     */
    public function __construct(HttpClient $httpClient, $model, $url, DeserializerInterface $deserializer = null)
    {
        $this->httpClient = $httpClient;
        $this->model = $model;
        $this->url = $url;
        $this->deserializer = $deserializer;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        // TODO: WE don't need it
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token) // @codingStandardsIgnoreLine
    {
        $model = $this->createModel();
        $response = $this->httpClient->createRequest(
            $this->url,
            'GET',
            $headers = ['Authorization' => 'Bearer '.$token]
        )->sendRequest();

        if ($response->getStatusCode() == 200) {
            return $this->deserializerContent($model, $response->getBody()->getContents());
        } else {
            throw  new HttpException($response->getStatusCode(), $response->getBody()->getContents());
        }
    }

    /**
     * Deserializer Content function
     *
     * @param $model
     * @param $bodyContent
     * @return Authenticatable|null
     */
    public function deserializerContent($model, $bodyContent)
    {
        if (! $this->deserializer) {
            $this->deserializer = new Deserializer();
        }

        $this->deserializer->setModel($model);

        return $this->deserializer->convert($bodyContent);
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        // TODO:  WE don't need it
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        // TODO:  WE don't need it
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // TODO:  WE don't need it
    }

    /**
     * Create a new instance of the model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createModel()
    {
        $class = '\\'.ltrim($this->model, '\\');

        return new $class();
    }

    /**
     * Sets the name of the Eloquent user model.
     *
     * @param  string  $model
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Gets the name of the Eloquent user model.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set http client function
     *
     * @param  HttpClient  $httpClient
     */
    public function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Set deserializer function
     *
     * @param  mixed  $deserializer
     */
    public function setDeserializer($deserializer): void
    {
        $this->deserializer = $deserializer;
    }
}
