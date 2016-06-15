<?php

namespace Markup\RabbitMq\ManagementApi;

use Guzzle\Http\Client as GuzzleHttpClient;

/**
 * ManagementApi
 *
 * @author Richard Fullmer <richard.fullmer@opensoftdev.com>
 */
class Client
{
    /**
     * @var GuzzleHttpClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    const USERNAME_DEFAULT = 'guest';

    /**
     * @var string
     */
    const PASSWORD_DEFAULT = 'guest';

    /**
     * @param \Guzzle\Http\Client $client
     * @param string              $username
     * @param string              $password
     */
    public function __construct(
        GuzzleHttpClient $client = null,
        $username = self::USERNAME_DEFAULT,
        $password =  self::PASSWORD_DEFAULT
    ) {
        $this->client = $client;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param  string|array          $endpoint Resource URI.
     * @param  string                $method
     * @param  array                 $headers  HTTP headers
     * @param  string|resource|array $body     Entity body of request (POST/PUT) or response (GET)
     * @return array
     */
    public function send($endpoint, $method = 'GET', $headers = null, $body = null)
    {
        if (null !== $body) {
            $body = json_encode($body);
        }

        $request = $this->client->createRequest($method, $endpoint, $headers, $body)->setAuth($this->username, $this->password);

        if (in_array($method, ['PUT', 'POST', 'DELETE'])) {
            $request->setHeader('content-type', 'application/json');
        }

        $response = $request->send();

        return json_decode($response->getBody(), true);
    }
}
