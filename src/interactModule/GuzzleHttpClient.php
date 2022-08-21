<?php

namespace Amin3536\LaravelApiUserProvider\interactModule;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;

/**
 * GuzzleHttpClient class.
 *
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
     * GuzzleHttpClient constructor.
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
     * Set default headers function.
     *
     * @param  array  $defaultHeaders
     */
    public function setDefaultHeaders($defaultHeaders)
    {
        $this->defaultHeaders = $defaultHeaders;
    }

    /**
     * Send request function.
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws GuzzleException
     */
    public function sendRequest(array $options = [])
    {
        try {
            return $this->client->send($this->request, $options);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse();
            }
            throw $e;
        }
    }

    /**
     * Create request function.
     *
     * @param $uri
     * @param  string  $method
     * @param  array  $headers
     * @param  null  $body
     * @param  array  $options
     * @return HttpClient
     */
    public function createRequest(// @codingStandardsIgnoreLine
        $uri,
        $method = self::METHOD_GET,
        array $headers = [],
        $body = null,
        array $options = []
    ): HttpClient {
        $resultHeaders = array_merge($this->defaultHeaders, $headers);
        $this->request = new Request($method, $uri, $resultHeaders, $body);

        return $this;
    }

    /**
     * Set client function.
     *
     * @param $client
     * @return \phpDocumentor\Reflection\Types\Void_|void
     */
    public function setClient($client)
    {
        $this->client = $client;
    }
}
