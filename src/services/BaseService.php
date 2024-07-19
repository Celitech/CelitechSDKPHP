<?php

namespace Celitech\Services;

use Celitech\Environment;
use Celitech\Retry;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

class BaseService
{
    protected $client;
    protected string $baseUrl;
    protected array $options;

    public function __construct(
        string $environment = Environment::Default,
        string $clientId = '',
        string $clientSecret = ''
    ) {
        $this->options = [
            'headers' => [],
        ];

        $this->baseUrl = $environment;

        $stack = HandlerStack::create();

        $stack->push(Retry::factory());

        $this->client = new Client([
            'handler' => $stack,
        ]);
    }

    protected function sendRequest($method, $uri, array $options = [])
    {
        $response = $this->client->request(
            $method,
            $this->baseUrl . $uri,
            array_replace_recursive($this->options, $options)
        );
        return $response->getBody()->getContents();
    }

    public function setBaseUrl(string $url): void
    {
        $this->baseUrl = $url;
    }
}
