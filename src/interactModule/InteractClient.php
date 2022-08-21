<?php
/**
 * Created by PhpStorm.
 * User: amin
 * Date: 2/15/21
 * Time: 12:48 PM.
 */

namespace Amin3536\LaravelApiUserProvider\interactModule;

/**
 * InteractClient class
 * todo : we dont have any complicated logic and can remove Main context ( this class  ) review it please  :).
 *
 * @property HttpClient client
 */
class InteractClient
{
    /**
     * Client variable.
     *
     * @var HttpClient
     */
    private $client;

    /**
     * InteractClient constructor.
     *
     * @param  HttpClient  $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * Set client function.
     *
     * @param  HttpClient  $client
     */
    public function setClient(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * Request function
     * todo : we dont have any complicated logic and can remove Main context ( this class  ).
     *
     * @return mixed
     */
    public function request()
    {
        return $this->client->sendRequest();
    }
}
