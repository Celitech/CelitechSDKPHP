<?php

namespace Celitech\Services;

use Celitech\Environment;
use Celitech\Retry;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Celitech\OAuth\TokenManager;
use Celitech\Utils\LineDecoder;

/**
 * Base service class providing core HTTP request functionality for all API services.
 *
 * This class handles HTTP client initialization, request execution, authentication,
 * retry logic, hooks, and streaming responses. All generated service classes extend
 * this base service to inherit common functionality.
 */
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

    /**
     * Send a synchronous HTTP request and return the response.
     *
     * This method merges service-level options with request-specific options,
     * constructs the full URL, and executes the request through the HTTP client.
     *
     * @param string $method HTTP method (GET, POST, PUT, DELETE, etc.)
     * @param string $uri Request URI path (relative to baseUrl)
     * @param array $options Additional request options (headers, body, query params, etc.)
     * @return \Psr\Http\Message\ResponseInterface The HTTP response
     */
    protected function sendRequest(
        string $method,
        string $uri,
        array $options = []
    ): \Psr\Http\Message\ResponseInterface {
        return $this->client->request(
            $method,
            $this->baseUrl . $uri,
            array_replace_recursive($this->options, $options)
        );
    }

    /**
     * Send a streaming request and yield response chunks as they arrive.
     *
     * @param string $method HTTP method
     * @param string $uri Request URI
     * @param array $options Request options
     * @return \Generator Generator yielding response chunks
     */
    protected function sendStreamingRequest(string $method, string $uri, array $options = []): \Generator
    {
        $options['stream'] = true;
        $response = $this->client->request(
            $method,
            $this->baseUrl . $uri,
            array_replace_recursive($this->options, $options)
        );

        $body = $response->getBody();
        $contentType = $response->getHeaderLine('Content-Type');
        $isEventStream = strpos($contentType, 'text/event-stream') !== false;

        $lineDecoder = new LineDecoder();

        // Stream chunks from the response body
        while (!$body->eof()) {
            $chunk = $body->read(1024);
            if ($chunk === '') {
                continue;
            }

            $lines = $lineDecoder->splitLines($chunk);
            foreach ($lines as $line) {
                if ($isEventStream) {
                    // For event-stream, parse data: prefix
                    $trimmedLine = trim($line);
                    if (strpos($trimmedLine, 'data: ') === 0) {
                        $data = substr($trimmedLine, 6);
                        yield $data;
                    }
                } else {
                    // For regular JSON responses, yield the line as-is
                    yield $line;
                }
            }
        }

        // Flush any remaining buffered data
        $remainingLines = $lineDecoder->flush();
        foreach ($remainingLines as $line) {
            if ($isEventStream) {
                $trimmedLine = trim($line);
                if (strpos($trimmedLine, 'data: ') === 0) {
                    $data = substr($trimmedLine, 6);
                    yield $data;
                }
            } else {
                yield $line;
            }
        }
    }

    /**
     * Send a streaming request and yield wrapped response chunks with headers.
     *
     * @param string $method HTTP method
     * @param string $uri Request URI
     * @param array $options Request options
     * @param callable $deserializer Function to deserialize each chunk into the expected type
     * @param string $responseWrapperClass The fully qualified class name for wrapping responses
     * @return \Generator Generator yielding wrapped response chunks
     */
    protected function sendWrappedStreamingRequest(
        string $method,
        string $uri,
        array $options,
        callable $deserializer,
        string $responseWrapperClass,
        string $metadataClass
    ): \Generator {
        $options['stream'] = true;
        $response = $this->client->request(
            $method,
            $this->baseUrl . $uri,
            array_replace_recursive($this->options, $options)
        );

        // Extract metadata once for all chunks
        $metadata = new $metadataClass(
            headers: $this->parseHeaders($response->getHeaders()),
            statusCode: $response->getStatusCode()
        );

        $body = $response->getBody();
        $contentType = $response->getHeaderLine('Content-Type');
        $isEventStream = strpos($contentType, 'text/event-stream') !== false;

        $lineDecoder = new LineDecoder();

        // Stream chunks from the response body
        while (!$body->eof()) {
            $chunk = $body->read(1024);
            if ($chunk === '') {
                continue;
            }

            $lines = $lineDecoder->splitLines($chunk);
            foreach ($lines as $line) {
                $data = null;
                if ($isEventStream) {
                    // For event-stream, parse data: prefix
                    $trimmedLine = trim($line);
                    if (strpos($trimmedLine, 'data: ') === 0) {
                        $data = substr($trimmedLine, 6);
                    }
                } else {
                    // For regular JSON responses, use the line as-is
                    $data = $line;
                }

                if ($data !== null) {
                    $deserializedData = $deserializer($data);
                    yield new $responseWrapperClass(data: $deserializedData, raw: $response, metadata: $metadata);
                }
            }
        }

        // Flush any remaining buffered data
        $remainingLines = $lineDecoder->flush();
        foreach ($remainingLines as $line) {
            $data = null;
            if ($isEventStream) {
                $trimmedLine = trim($line);
                if (strpos($trimmedLine, 'data: ') === 0) {
                    $data = substr($trimmedLine, 6);
                }
            } else {
                $data = $line;
            }

            if ($data !== null) {
                $deserializedData = $deserializer($data);
                yield new $responseWrapperClass(data: $deserializedData, raw: $response, metadata: $metadata);
            }
        }
    }

    /**
     * Parse and normalize HTTP response headers.
     *
     * Converts header values from arrays to single strings when only one value exists,
     * while preserving arrays for multi-value headers (e.g., Set-Cookie).
     *
     * @param array $headers Raw header array from response (name => array of values)
     * @return array Normalized headers (name => string or array)
     */
    protected function parseHeaders(array $headers): array
    {
        $parsed = [];
        foreach ($headers as $name => $values) {
            // Flatten single-value arrays, keep multi-value as array
            $parsed[$name] = count($values) === 1 ? $values[0] : $values;
        }
        return $parsed;
    }

    /**
     * Update the base URL for API requests.
     *
     * This method allows changing the API endpoint at runtime, useful for
     * switching environments or API versions.
     *
     * @param string $url The new base URL (e.g., 'https://api.example.com/v2')
     * @return void
     */
    public function setBaseUrl(string $url): void
    {
        $this->baseUrl = $url;
    }

    /**
     * Set the request timeout in milliseconds.
     *
     * Updates the HTTP client with a new timeout value. This affects all subsequent
     * requests made through this service instance.
     *
     * @param float $timeout Timeout in milliseconds
     * @return void
     */
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
