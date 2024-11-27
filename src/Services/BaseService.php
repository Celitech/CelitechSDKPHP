<?php

namespace Celitech\Services;

use Celitech\Environment;
use Celitech\Retry;
use Celitech\Hooks\CustomHook;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

class BaseService
{
    protected $client;
    protected string $baseUrl;
    protected array $options;
    protected HandlerStack $stack;

    public function __construct(
        string $environment = Environment::Default,
        float $timeout = 0,
        string $clientId = '',
        string $clientSecret = ''
    ) {
        $this->options = [
            'headers' => [],
        ];

        $this->baseUrl = $environment;

        $stack = HandlerStack::create();

        $stack->push(Retry::factory());

        $hook = new CustomHook(['clientId' => $clientId, 'clientSecret' => $clientSecret]);
        $stack->push($this->hookMiddleware($hook));

        $this->stack = $stack;

        $this->client = new Client([
            'handler' => $stack,
            'timeout' => $timeout / 1000,
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

    public function setTimeout(float $timeout): void
    {
        $this->client = new Client([
            'handler' => $this->stack,
            'timeout' => $timeout / 1000,
        ]);
    }

    private function hookMiddleware(CustomHook $hook)
    {
        return function (callable $handler) use ($hook) {
            return function (RequestInterface $request, array $options) use ($handler, $hook) {
                $hook->beforeRequest($request);

                return $handler($request, $options)->then(
                    function (ResponseInterface $response) use ($request, $hook) {
                        $hook->afterResponse($request, $response);
                        return $response;
                    },
                    function ($reason) use ($request, $hook) {
                        if ($reason instanceof Exception) {
                            $hook->onError($request, $reason);
                        }
                    }
                );
            };
        };
    }
}
