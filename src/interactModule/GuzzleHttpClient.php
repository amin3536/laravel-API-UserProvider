<?php

namespace Amin3536\LaravelApiUserProvider\interactModule;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;

/**
 * @property Client client
 * @property Request request
 */
class GuzzleHttpClient implements HttpClient
{
    private $request;
    private $defaultHeaders = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ];

    /**
     * GuzzelHttpClient constructor.
     *
     * @param  string  $baseUrl
     */
    public function __construct($baseUrl, $timeout)
    {
        $this->client = new Client([
            'base_uri' => $baseUrl,
            'timeout' => $timeout,
        ]);
    }

    /**
     * @param  array  $defaultHeaders
     */
    public function setDefaultHeaders($defaultHeaders)
    {
        $this->defaultHeaders = $defaultHeaders;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws GuzzleException
     */
    public function sendRequest()
    {
        try {
            return $this->client->send($this->request);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse();
            }
            throw $e;
        }
    }

    /**
     * @param $uri
     * @param  string  $method
     * @param  array  $headers
     * @param  null  $body
     * @return HttpClient
     */
    public function createRequest($uri, $method = self::METHOD_GET, array $headers = [], $body = null, array $options = []): HttpClient
    {
        $resultHeaders = ['headers' => array_merge($this->defaultHeaders, $headers)];
        $resultOptions = array_merge($resultHeaders, $options);
        $this->request = new Request($method, $uri, $resultOptions, $body);

        return $this;
    }

    public function setClient( $client )
    {
        $this->client = $client;
    }
}
