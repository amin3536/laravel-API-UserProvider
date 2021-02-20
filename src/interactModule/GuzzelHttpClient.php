<?php

namespace App\Modules\interactModule;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;

/**
 * @property Client client
 * @property Request request
 */
class GuzzelHttpClient implements HttpClient
{
    private $request;
    private $defaultHeaders = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ];

    /**
     * GuzzelHttpClient constructor.
     * @param string $baseUrl
     */
    public function __construct($baseUrl)
    {
        $this->client = new Client([
            'base_uri' => $baseUrl,
            'timeout' => 2.0,
        ]);
    }

    /**
     * @param array $defaultHeaders
     */
    public function setDefaultHeaders($defaultHeaders)
    {
        $this->defaultHeaders = $defaultHeaders;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     * @throws GuzzleException
     */
    public function sendRequest()
    {
        return $this->client->send($this->request);
    }

    /**
     * @param $uri
     * @param string $method
     * @param array $headers
     * @param null $body
     * @return HttpClient
     */
    public function createRequest($uri, $method = self::METHOD_GET, array $headers = [], $body = null): HttpClient
    {
        $resultHeaders = array_merge($this->defaultHeaders, $headers);
        $this->request = new Request($method, $uri, $resultHeaders, $body);

        return $this;
    }
}
