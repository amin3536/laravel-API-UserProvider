<?php


namespace App\Modules\interactModule;


interface HttpClient
{
    public const METHOD_HEAD = 'HEAD';
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_PURGE = 'PURGE';
    public const METHOD_OPTIONS = 'OPTIONS';
    public const METHOD_TRACE = 'TRACE';
    public const METHOD_CONNECT = 'CONNECT';





    /**
     * HttpClient constructor.
     * @param string $baseUrl
     */
    public function __construct($baseUrl);

    /**
     * @return mixed
     */
    public function sendRequest();

    /**
     * @param $uri
     * @param string $method
     * @param array $headers
     * @param null $body
     * @return HttpClient
     */
    public function createRequest($uri, $method = self::METHOD_GET, array $headers = [], $body = null):HttpClient;
}
