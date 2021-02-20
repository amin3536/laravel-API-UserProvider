<?php
/**
 * Created by PhpStorm.
 * User: amin
 * Date: 2/15/21
 * Time: 12:48 PM.
 */

namespace Amin3536\LaravelApiUserProvider\interactModule;

/**
 * @property HttpClient client
 * //todo : we dont have any complicated logic and can remove Main context ( this class  ) review it please  :)
 */
class InteractClient
{
    private $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    public function setClient(HttpClient $client)
    {
        $this->client = $client;
    }

    //todo : we dont have any complicated logic and can remove Main context ( this class  )
    public function request()
    {
        return $this->client->sendRequest();
    }
}
