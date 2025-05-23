<?php

namespace Celitech\Services;

use Celitech\Environment;
use Celitech\Retry;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Celitech\OAuth\TokenManager;

class BaseService
{
    protected $client;
    protected string $baseUrl;
    protected array $options;
    protected HandlerStack $stack;

    private TokenManager $tokenManager;

    public function __construct(
        string $environment = Environment::Default,
        float $timeout = 0,
        TokenManager $tokenManager = null
    ) {
        $this->options = [
            'headers' => [],
        ];

        $this->baseUrl = $environment;

        $stack = HandlerStack::create();

        $stack->push(Retry::factory());

        if ($tokenManager) {
            $this->tokenManager = $tokenManager;
            $stack->push(middleware: $this->oauthMiddleware());
        }

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

    private function oauthMiddleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                $scopes = $options['scopes'] ?? null;
                if (is_array($scopes)) {
                    $token = $this->tokenManager->getToken($scopes);

                    $request = $request->withHeader('Authorization', 'Bearer ' . $token->accessToken);
                }

                return $handler($request, $options);
            };
        };
    }
}
