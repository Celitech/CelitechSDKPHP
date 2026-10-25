<?php

declare(strict_types=1);

namespace Celitech\Services;

use Celitech\Environments;
use Celitech\Exceptions\CelitechApiException;
use Celitech\Exceptions\TimeoutException;
use Celitech\Exceptions\CelitechException;
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
  protected \GuzzleHttp\Client $client;
  protected string $baseUrl;
  protected array $options;
  protected HandlerStack $stack;
  /** @var array|null Service-level configuration overrides */
  protected ?array $serviceConfig = null;
  /** The root client's RawClient, shared by every service. Null on the Guzzle-only path. */
  protected ?\Celitech\Core\Client\RawClient $rawClient = null;

  private TokenManager $tokenManager;

  public function __construct(
    \Celitech\Core\Client\RawClient $client,
    ?array $options = null,
    ?TokenManager $tokenManager = null
  ) {
    $this->rawClient = $client;
    $this->options = $options ?? [];
    $this->options['headers'] = array_merge(
      [
        'X-Fern-Language' => 'PHP',
        'X-Fern-SDK-Name' => 'Celitech'
      ],
      $this->options['headers'] ?? []
    );
    $httpClient = $this->options['client'] ?? null;

    $environment = $this->options['baseUrl'] ?? Environments::Default_->value;
    $this->baseUrl = $environment instanceof Environments ? $environment->value : $environment;

    $baseHandler = null;
    if ($httpClient !== null) {
      $baseHandler = function (\Psr\Http\Message\RequestInterface $request, array $options) use (
        $httpClient
      ) {
        try {
          $response = $httpClient->sendRequest($request);
          return \GuzzleHttp\Promise\Create::promiseFor($response);
        } catch (\Psr\Http\Client\ClientExceptionInterface $e) {
          return \GuzzleHttp\Promise\Create::rejectionFor(
            new \GuzzleHttp\Exception\RequestException($e->getMessage(), $request, null, $e)
          );
        }
      };
    }

    $stack = HandlerStack::create($baseHandler);
    $stack->push(
      Retry::factory(
        isset($this->options['maxRetries']) ? ['maxRetries' => $this->options['maxRetries']] : []
      )
    );

    if ($tokenManager) {
      $this->tokenManager = $tokenManager;
      $stack->push(middleware: $this->oauthMiddleware());
    }

    $this->stack = $stack;

    $this->client = new Client([
      'handler' => $stack,
      'timeout' => $this->options['timeout'] ?? 30
    ]);
  }

  /**
   * Send a synchronous HTTP request and return the response.
   *
   * This method merges service-level options with request-specific options,
   * applies configuration overrides, constructs the full URL, and executes the request.
   * If the request fails with an HTTP error, it wraps the error in CelitechApiException
   * containing the status code, response body, and headers.
   *
   * @param string $method HTTP method (GET, POST, PUT, DELETE, etc.)
   * @param string $uri Request URI path (relative to baseUrl)
   * @param array $options Additional request options (headers, body, query params, etc.)
   * @param array $resolvedConfig Resolved configuration from hierarchy
   * @return \Psr\Http\Message\ResponseInterface The HTTP response
   * @throws CelitechApiException When the HTTP request returns an error response
   */
  protected function sendRequest(
    string $method,
    string $uri,
    array $options = [],
    array $resolvedConfig = []
  ): \Psr\Http\Message\ResponseInterface {
    $baseUrl = $this->getBaseUrl($resolvedConfig);
    $rawRequest = $this->toRawRequest($method, $baseUrl, $uri, $options);
    if ($rawRequest !== null) {
      return $this->dispatchRaw($rawRequest, $resolvedConfig);
    }
    $mergedOptions = $this->applyConfig($options, $resolvedConfig);
    try {
      return $this->client->request($method, $baseUrl . $uri, $mergedOptions);
    } catch (\GuzzleHttp\Exception\ConnectException $e) {
      if (strpos($e->getMessage(), 'cURL error 28') !== false) {
        throw new TimeoutException($e->getMessage(), previous: $e);
      }
      throw new CelitechException(message: $e->getMessage(), previous: $e);
    } catch (\GuzzleHttp\Exception\RequestException $e) {
      $this->wrapRequestException($e);
    }
  }

  /**
   * Send a streaming request and yield response chunks as they arrive.
   *
   * @param string $method HTTP method
   * @param string $uri Request URI
   * @param array $options Request options
   * @return \Generator Generator yielding response chunks
   */
  /**
   * The fernMode JSON seam: dispatches through `Core\Client\RawClient` instead of the Guzzle
   * handler stack, and is the single place PSR-18 faults are translated into this SDK's own
   * exception types. Streaming, paginated, multipart and error-mapped methods still use
   * `sendRequest` above, so a service class may legitimately use both.
   *
   * RawClient never throws on an HTTP status — Guzzle's `http_errors` has no equivalent — so the
   * >= 300 check here is what keeps a 3xx/4xx from deserializing into a successful return.
   */
  protected function sendJsonRequest(
    \Celitech\Core\Client\BaseApiRequest $request,
    array $resolvedConfig = []
  ): \Psr\Http\Message\ResponseInterface {
    return $this->dispatchRaw($request, $resolvedConfig);
  }

  /**
   * The single RawClient dispatch used by every fernMode transport helper.
   *
   * `sendJsonRequest` and the fernMode branch of `sendRequest` both land here, so PSR-18 fault
   * translation and the >= 300 check exist once rather than once per helper. A 3xx counts: the
   * transport does not follow redirects, so its empty body is not a result.
   *
   * `$stream` is true only from `openSseConnection`: every ordinary JSON/multipart/raw-body call
   * leaves it false, which is exactly the option bag `toRawOptions` built before FSDK-2151.
   */
  private function dispatchRaw(
    \Celitech\Core\Client\BaseApiRequest $request,
    array $resolvedConfig = [],
    bool $stream = false
  ): \Psr\Http\Message\ResponseInterface {
    if ($this->rawClient === null) {
      throw new CelitechApiException(
        message: 'A RawClient is required for the JSON transport but none was provided.',
        statusCode: 0
      );
    }

    try {
      $response = $this->rawClient->sendRequest(
        $request,
        $this->toRawOptions($resolvedConfig, $stream)
      );
    } catch (\Psr\Http\Client\ClientExceptionInterface $e) {
      // Preserved from the Guzzle path: a read timeout still surfaces as TimeoutException
      // rather than a generic transport fault. Guzzle's ConnectException implements
      // NetworkExceptionInterface, which extends ClientExceptionInterface, and keeps the
      // 'cURL error 28' text in its message.
      if (strpos($e->getMessage(), 'cURL error 28') !== false) {
        throw new TimeoutException($e->getMessage(), previous: $e);
      }
      throw new CelitechException(message: $e->getMessage(), previous: $e);
    }

    $statusCode = $response->getStatusCode();
    if ($statusCode >= 300) {
      throw new CelitechApiException(
        message: "API error: {$statusCode}",
        statusCode: $statusCode,
        responseBody: (string) $response->getBody(),
        responseHeaders: $response->getHeaders()
      );
    }

    return $response;
  }

  /**
   * Maps `sendRequest`'s Guzzle-style options bag onto a RawClient request, or null when the call
   * has to stay on the Guzzle path.
   *
   * The key check is an allowlist rather than a blocklist: `sink` and a per-call `timeout` are
   * the two Guzzle-only options this bridge cannot carry (`timeout` rides `$resolvedConfig`
   * instead, via `toRawOptions`), and recognising only what can be translated means an unhandled
   * option leaves the call on Guzzle intact — a blocklist would instead drop the payload into an empty JSON request and
   * fail silently at runtime.
   *
   * @param array<string, mixed> $options
   */
  private function toRawRequest(
    string $method,
    string $baseUrl,
    string $uri,
    array $options
  ): ?\Celitech\Core\Client\BaseApiRequest {
    if ($this->rawClient === null) {
      return null;
    }

    if (
      array_diff(array_keys($options), [
        'headers',
        'query',
        'json',
        'form_params',
        'multipart',
        'body'
      ]) !== []
    ) {
      return null;
    }

    $httpMethod = $this->toHttpMethod($method);
    if ($httpMethod === null) {
      return null;
    }

    $headers = is_array($options['headers'] ?? null) ? $options['headers'] : [];
    $query = is_array($options['query'] ?? null) ? $options['query'] : [];

    // `array_key_exists`, not `isset`: an optional multipart/raw body renders as `null` (e.g.
    // `$request?->toMultipart()`), and that null IS the "no body" request, not a cue to fall
    // through to the next branch and build the wrong request type.
    if (array_key_exists('multipart', $options)) {
      $multipart = $options['multipart'];
      return new \Celitech\Core\Multipart\MultipartApiRequest(
        baseUrl: $baseUrl,
        path: $uri,
        method: $httpMethod,
        headers: $headers,
        query: $query,
        body: is_array($multipart) ? $this->toMultipartFormData($multipart) : null
      );
    }

    if (array_key_exists('body', $options)) {
      return new \Celitech\Core\Client\RawBodyApiRequest(
        baseUrl: $baseUrl,
        path: $uri,
        method: $httpMethod,
        headers: $headers,
        query: $query,
        body: $options['body']
      );
    }

    if (isset($options['form_params'])) {
      return new \Celitech\Core\Client\UrlEncodedApiRequest(
        baseUrl: $baseUrl,
        path: $uri,
        method: $httpMethod,
        headers: $headers,
        query: $query,
        body: $options['form_params']
      );
    }

    return new \Celitech\Core\Json\JsonApiRequest(
      baseUrl: $baseUrl,
      path: $uri,
      method: $httpMethod,
      headers: $headers,
      query: $query,
      body: array_key_exists('json', $options) ? $options['json'] : null
    );
  }

  /**
   * HttpMethod is a pure enum, so there is no `from()`; an unrecognised verb returns null and
   * leaves the call on the Guzzle path rather than becoming a fatal.
   */
  private function toHttpMethod(string $method): ?\Celitech\Core\Client\HttpMethod
  {
    return match (strtoupper($method)) {
      'GET' => \Celitech\Core\Client\HttpMethod::GET,
      'POST' => \Celitech\Core\Client\HttpMethod::POST,
      'PUT' => \Celitech\Core\Client\HttpMethod::PUT,
      'PATCH' => \Celitech\Core\Client\HttpMethod::PATCH,
      'DELETE' => \Celitech\Core\Client\HttpMethod::DELETE,
      'HEAD' => \Celitech\Core\Client\HttpMethod::HEAD,
      'OPTIONS' => \Celitech\Core\Client\HttpMethod::OPTIONS,
      'TRACE' => \Celitech\Core\Client\HttpMethod::TRACE,
      default => null
    };
  }

  /**
   * Converts a generated model's `toMultipart()` output — an array of Guzzle-shaped parts
   * (`['name' => ..., 'contents' => ..., 'filename' => ..., 'headers' => ...]`) — into the
   * `MultipartFormData` RawClient's transport takes.
   *
   * The model layer still targets Guzzle's array shape regardless of fernMode, so this bridges
   * the two rather than the model changing what it builds.
   * `Core\Multipart\MultipartFormDataPart::toGuzzlePart()` is the mirror of this conversion.
   *
   * @param array<int, array<string, mixed>> $parts
   */
  private function toMultipartFormData(array $parts): \Celitech\Core\Multipart\MultipartFormData
  {
    $formData = new \Celitech\Core\Multipart\MultipartFormData();
    foreach ($parts as $part) {
      $formData->addPart(
        new \Celitech\Core\Multipart\MultipartFormDataPart(
          name: $part['name'],
          value: $part['contents'],
          filename: $part['filename'] ?? null,
          headers: $part['headers'] ?? null
        )
      );
    }
    return $formData;
  }

  /**
   * Translates this SDK's resolved config into RawClient's per-call options bag.
   *
   * `timeout` is in seconds and passed through unconverted, as Fern's RawClient does with the
   * same option: a Fern caller's per-call `['timeout' => 3.0]` is three seconds here too.
   *
   * Header precedence is the order pinned by BaseService-fern-mode.spec.ts — service defaults,
   * then per-call headers, then per-call credentials, which win. `baseUrl` is deliberately NOT
   * placed here; it belongs on the request object.
   *
   * @param array<string, mixed> $resolvedConfig
   * @param bool $stream See `dispatchRaw`; forwarded to RawClient as the `stream` option so an
   *     SSE/streaming read's underlying transport is asked for an unbuffered body (FSDK-2151).
   * @return array<string, mixed>
   */
  private function toRawOptions(array $resolvedConfig, bool $stream = false): array
  {
    $options = [];

    if ($stream) {
      $options['stream'] = true;
    }

    if (isset($resolvedConfig['timeout'])) {
      $options['timeout'] = $resolvedConfig['timeout'];
    } elseif (isset($this->options['timeout'])) {
      // The service-level default, which `setTimeout()` writes on the RawClient-only path.
      $options['timeout'] = $this->options['timeout'];
    }
    // Fern's per-call `maxRetries` (the one the README documents) and this SDK's own
    // `retryConfig.maxRetries`: the policy object is the more specific, so it wins when both
    // are given. `??` and not `isset`-then-assign, so an explicit `0` (one attempt) survives.
    $maxRetries =
      $resolvedConfig['retryConfig']['maxRetries'] ?? ($resolvedConfig['maxRetries'] ?? null);
    if ($maxRetries !== null) {
      $options['maxRetries'] = $maxRetries;
    }
    if (is_array($resolvedConfig['retryConfig'] ?? null)) {
      $options['retry'] = array_intersect_key(
        $resolvedConfig['retryConfig'],
        array_flip([
          'isEnabled',
          'retryableStatuses',
          'retryableMethods',
          'baseDelayMs',
          'delayMultiplier',
          'maxDelayMs',
          'delayJitter'
        ])
      );
    }
    // Fern's per-call escape hatch for undeclared body fields. RawClient merges it over the
    // encoded JSON (and form-urlencoded) body, caller's keys winning; without this forward it
    // never leaves `$resolvedConfig` and the option is silently dropped.
    if (is_array($resolvedConfig['bodyProperties'] ?? null)) {
      $options['bodyProperties'] = $resolvedConfig['bodyProperties'];
    }

    $headers = array_replace(
      is_array($this->options['headers'] ?? null) ? $this->options['headers'] : [],
      is_array($resolvedConfig['headers'] ?? null) ? $resolvedConfig['headers'] : [],
      $this->perCallCredentialHeaders($resolvedConfig)
    );
    if ($headers !== []) {
      $options['headers'] = $headers;
    }

    // Fern's per-call `queryParameters`. RawClient merges them over the request's own query, so
    // declared parameters and the caller's extras both reach the wire.
    $query = is_array($resolvedConfig['queryParameters'] ?? null)
      ? $resolvedConfig['queryParameters']
      : [];

    if ($query !== []) {
      $options['queryParameters'] = $query;
    }

    return $options;
  }

  /**
   * Per-call credential overrides only. Service-level credentials are already in
   * `$this->options['headers']`; these are merged last so a credential passed for one call beats
   * an `Authorization` header passed alongside it.
   *
   * @param array<string, mixed> $resolvedConfig
   * @return array<string, string>
   */
  private function perCallCredentialHeaders(array $resolvedConfig): array
  {
    $headers = [];
    return $headers;
  }

  /**
   * Folds one line of a `text/event-stream` body into the frame being accumulated, returning that
   * frame's payload when the line terminated it and null otherwise.
   *
   * Shared by the read loop and the end-of-body flush, which see the same lines from different
   * sources — keeping the framing in one place is what lets a field like `event:` be added once.
   *
   * @param array{data: array<string>, event: string, id: string, retry: ?int, sawData: bool} $acc
   * @return ?array{data: string, event: string, id: string, retry: ?int} The completed frame, or
   *   null when it is still open
   */
  private function consumeSseLine(string $line, array &$acc): ?array
  {
    // Strips the delimiter LineDecoder keeps on the line. Greedy over CR and LF is safe: the
    // split is on those characters, so the content itself can hold none of them.
    $line = rtrim($line, "\r\n");

    if ($line === '') {
      return $this->takeSseFrame($acc);
    }

    // A comment line. Keep-alives arrive as a bare `:`, and the spec says ignore.
    if (str_starts_with($line, ':')) {
      return null;
    }

    [$field, $value] = $this->splitSseField($line);

    if ($field === 'data') {
      $acc['data'][] = $value;
      $acc['sawData'] = true;
    } elseif ($field === 'event') {
      $acc['event'] = $value;
    } elseif ($field === 'id' && !str_contains($value, "\0")) {
      // No reset between frames: the WHATWG spec makes the last id the stream's
      // "last event ID", which a frame declaring none inherits.
      $acc['id'] = $value;
    } elseif ($field === 'retry') {
      // Spec says ignore a retry that is not all digits, rather than guessing at it.
      $acc['retry'] = ctype_digit($value) ? (int) $value : $acc['retry'];
    }

    return null;
  }

  /**
   * Splits `field: value` per the spec: the first colon separates them, exactly ONE optional
   * space after it is framing, and a line with no colon is a field with an empty value.
   *
   * @return array{0: string, 1: string}
   */
  private function splitSseField(string $line): array
  {
    $colon = strpos($line, ':');
    if ($colon === false) {
      return [$line, ''];
    }

    $value = substr($line, $colon + 1);
    if (str_starts_with($value, ' ')) {
      $value = substr($value, 1);
    }

    return [substr($line, 0, $colon), $value];
  }

  /**
   * Takes the buffered `data:` lines of one completed SSE frame, or null when no frame is open.
   *
   * A method rather than a closure on purpose: a closure capturing `$sawData` by reference has it
   * narrowed to the literal `false` it was initialised with, so static analysis reads the guard as
   * always true, the body as dead and the return type as `null`. Declared by-ref parameters carry
   * their declared types instead.
   *
   * @param array{data: array<string>, event: string, id: string, retry: ?int, sawData: bool} $acc
   * @return ?array{data: string, event: string, id: string, retry: ?int} The frame, its `data:`
   *   lines joined with a newline
   */
  private function takeSseFrame(array &$acc): ?array
  {
    if (!$acc['sawData']) {
      return null;
    }

    $frame = [
      'data' => implode("\n", $acc['data']),
      'event' => $acc['event'],
      // `id` deliberately survives: it is the stream's last event ID, not the frame's.
      'id' => $acc['id'],
      'retry' => $acc['retry']
    ];
    $acc['data'] = [];
    $acc['event'] = '';
    $acc['retry'] = null;
    $acc['sawData'] = false;

    return $frame;
  }

  protected function sendStreamingRequest(
    string $method,
    string $uri,
    array $options = [],
    array $resolvedConfig = [],
    ?bool $declaredSse = null
  ): \Generator {
    foreach (
      $this->streamSseFrames($method, $uri, $options, $resolvedConfig, $declaredSse)
      as $frame
    ) {
      yield $frame['data'];
    }
  }

  /**
   * The same body reader as `sendStreamingRequest`, yielding whole frames instead of just their
   * payloads, so a caller that needs `event:`/`id:`/`retry:` can have them. One producer rather
   * than two: the framing rules live in `consumeSseLine` and must not fork.
   *
   * A json-framed body has no frame metadata, so each line becomes a frame carrying only `data`.
   *
   * @return \Generator<int, array{data: string, event: string, id: string, retry: ?int}>
   */
  protected function streamSseFrames(
    string $method,
    string $uri,
    array $options = [],
    array $resolvedConfig = [],
    ?bool $declaredSse = null,
    bool $resumable = false,
    ?string $terminator = null
  ): \Generator {
    // `Accept: text/event-stream` only for a spec-DECLARED SSE stream. A json-framed stream
    // (`$declaredSse === false`) must not get it, and neither should one left to runtime
    // sniffing (`null`) — the server has not promised event-stream framing for either, and
    // JsonApiRequest's own `*/*` default is the honest ask in both cases.
    if ($declaredSse === true) {
      $options['headers'] = array_merge(
        ['Accept' => 'text/event-stream'],
        $options['headers'] ?? []
      );
    }
    $response = $this->openSseConnection($method, $uri, $options, $resolvedConfig);
    yield from $this->readSseBody(
      $response,
      $declaredSse,
      $method,
      $uri,
      $options,
      $resolvedConfig,
      $resumable,
      $terminator
    );
  }

  /**
   * Opens one SSE/JSON stream connection over whichever transport this service uses. Shared by
   * `streamSseFrames`'s initial request and every reconnect `readSseBody` issues after a
   * mid-stream drop, so the two can never build the request differently.
   *
   * Requests an UNBUFFERED body on both transports (FSDK-2151): Guzzle via `stream => true`, and reads
   * progressively below in `readSseBody`/`streamSseFrames` either way. A PSR-18 client that is
   * neither Guzzle nor Symfony has no portable streaming option — `RetryDecoratingClient::doSend`
   * documents that degradation (it is silent, not warned about), never breaking the call, just
   * buffering it as every request already was before this fix.
   */
  private function openSseConnection(
    string $method,
    string $uri,
    array $options,
    array $resolvedConfig
  ): \Psr\Http\Message\ResponseInterface {
    $baseUrl = $this->getBaseUrl($resolvedConfig);
    $options['stream'] = true;
    $mergedOptions = $this->applyConfig($options, $resolvedConfig);
    try {
      return $this->client->request($method, $baseUrl . $uri, $mergedOptions);
    } catch (\GuzzleHttp\Exception\ConnectException $e) {
      if (strpos($e->getMessage(), 'cURL error 28') !== false) {
        throw new TimeoutException($e->getMessage(), previous: $e);
      }
      throw new CelitechException(message: $e->getMessage(), previous: $e);
    } catch (\GuzzleHttp\Exception\RequestException $e) {
      $this->wrapRequestException($e);
    }
  }

  /**
   * Classifies one line of a JSON-framed (non-SSE) stream body.
   *
   * A json-framed stream carries no `data:` prefix, so ordinarily the line IS the payload — but it
   * may still legally carry SSE-style `id:`/`event:`/`retry:` metadata lines interleaved with the
   * JSON messages, and those must be consumed as metadata rather than reach the JSON decoder. Every
   * valid JSON value starts with one of `{[\"-0123456789tfn`, none of which collide with those three
   * field names, so the prefix match cannot misclassify a real payload line.
   *
   * @return ?array{data: string, event: string, id: string, retry: ?int} null for a metadata or
   *   blank line, the line as a bare-data frame otherwise
   */
  private function consumeJsonLine(string $line): ?array
  {
    $line = rtrim($line, "\r\n");
    if ($line === '') {
      return null;
    }

    [$field] = $this->splitSseField($line);
    if (in_array($field, ['id', 'event', 'retry'], true)) {
      return null;
    }

    return ['data' => $line, 'event' => '', 'id' => '', 'retry' => null];
  }

  /**
   * Reads frames off one response body and, for an operation the spec declares resumable,
   * reconnects with `Last-Event-ID` when the connection drops: the body read fails, or the body
   * ends cleanly before the operation's declared terminator arrived. A transport that cannot
   * surface a dropped connection (Guzzle's `stream => true` reads one as a clean end of body) is
   * covered by the second case. An operation that declares no terminator cannot tell a finished
   * stream from a dropped one, so it never reconnects, and neither does a stream that drops before
   * any event id was dispatched.
   *
   * The wait before each reconnect is the server's latest `retry:`, else one second, capped at
   * 30 seconds. Up to `maxStreamReconnectionAttempts` consecutive attempts (default 5) are made,
   * and the count resets whenever an event arrives. An SSE frame left unterminated when the body
   * ends or fails is never delivered, whether or not the stream reconnects.
   *
   * @return \Generator<int, array{data: string, event: string, id: string, retry: ?int}>
   */
  private function readSseBody(
    \Psr\Http\Message\ResponseInterface $response,
    ?bool $declaredSse,
    string $method,
    string $uri,
    array $options,
    array $resolvedConfig,
    bool $resumable,
    ?string $terminator
  ): \Generator {
    $contentType = $response->getHeaderLine('Content-Type');
    $isEventStream = $declaredSse ?? strpos($contentType, 'text/event-stream') !== false;
    /** @var ?callable(): bool $abortSignal */
    $abortSignal = $resolvedConfig['abortSignal'] ?? null;
    $policy = is_array($resolvedConfig['retryConfig'] ?? null)
      ? $resolvedConfig['retryConfig']
      : [];
    // A reconnect re-sends the request, so it obeys the same `retryableMethods` narrowing an
    // ordinary retry does: a caller who excluded POST is not re-POSTed on a drop either.
    $retryableMethods = $policy['retryableMethods'] ?? null;
    $mayReconnect =
      $resumable &&
      $isEventStream &&
      $this->streamReconnectionEnabled($resolvedConfig) &&
      (!is_array($retryableMethods) || in_array(strtoupper($method), $retryableMethods, true));
    $endsOnTerminator = $isEventStream && $terminator !== null;
    $maxReconnects = $mayReconnect ? $this->reconnectBudget($resolvedConfig) : 0;
    $reconnectAttempt = 0;
    $lastDispatchedId = null;
    $serverRetryMs = null;

    while (true) {
      $lineDecoder = new LineDecoder();
      $sseFrame = ['data' => [], 'event' => '', 'id' => '', 'retry' => null, 'sawData' => false];
      $body = $response->getBody();
      $dropped = null;

      while (!$body->eof()) {
        // Only the read is guarded: PSR-7 signals a failed read with RuntimeException, and
        // that is the one failure that means the connection dropped. Anything thrown while
        // framing what WAS read is a real error and propagates as one.
        try {
          $chunk = $body->read(1024);
        } catch (\RuntimeException $e) {
          $dropped = $e;
          break;
        }
        if ($chunk === '') {
          continue;
        }
        foreach ($lineDecoder->splitLines($chunk) as $line) {
          $frame = $isEventStream
            ? $this->consumeSseLine($line, $sseFrame)
            : $this->consumeJsonLine($line);
          if ($frame === null) {
            continue;
          }
          if ($endsOnTerminator && trim($frame['data']) === $terminator) {
            return;
          }
          if ($frame['id'] !== '') {
            $lastDispatchedId = $frame['id'];
          }
          $serverRetryMs = $frame['retry'] ?? $serverRetryMs;
          $reconnectAttempt = 0;
          yield $frame;
        }
      }

      $endedBeforeTerminator = $dropped === null && $mayReconnect && $endsOnTerminator;
      if ($dropped === null && !$endedBeforeTerminator) {
        if (!$isEventStream) {
          foreach ($lineDecoder->flush() as $line) {
            $frame = $this->consumeJsonLine($line);
            if ($frame !== null) {
              yield $frame;
            }
          }
        }
        return;
      }

      $resumes =
        $mayReconnect &&
        $endsOnTerminator &&
        $lastDispatchedId !== null &&
        $reconnectAttempt < $maxReconnects;
      if (!$resumes) {
        if ($dropped !== null) {
          throw $dropped;
        }
        return;
      }
      if ($abortSignal !== null && $abortSignal()) {
        return;
      }

      $reconnectAttempt++;
      $serverRetryMs = $sseFrame['retry'] ?? $serverRetryMs;
      if ($this->waitUnlessAborted($this->reconnectDelayMs($serverRetryMs), $abortSignal)) {
        return;
      }

      $reconnectOptions = $options;
      // Case-insensitive, as HTTP header names are: a caller-supplied `last-event-id`
      // is replaced rather than sent alongside ours.
      $headers = array_filter(
        $options['headers'] ?? [],
        static fn($name): bool => strcasecmp((string) $name, 'Last-Event-ID') !== 0,
        ARRAY_FILTER_USE_KEY
      );
      $reconnectOptions['headers'] = $headers + ['Last-Event-ID' => $lastDispatchedId];
      $response = $this->openSseConnection($method, $uri, $reconnectOptions, $resolvedConfig);
    }
  }

  /**
   * Whether a resumable stream reconnects at all: the per-call `streamReconnectionEnabled`, else
   * the client's, else true.
   */
  private function streamReconnectionEnabled(array $resolvedConfig): bool
  {
    return (bool) ($resolvedConfig['streamReconnectionEnabled'] ??
      ($this->options['streamReconnectionEnabled'] ?? true));
  }

  /**
   * The consecutive reconnects a drop may spend: the per-call `maxStreamReconnectionAttempts`, else
   * the client's, else 5. `maxRetries` governs ordinary HTTP retries only.
   */
  private function reconnectBudget(array $resolvedConfig): int
  {
    return (int) ($resolvedConfig['maxStreamReconnectionAttempts'] ??
      ($this->options['maxStreamReconnectionAttempts'] ?? 5));
  }

  /**
   * The wait before a reconnect: the server's latest `retry:` in milliseconds, else one second,
   * never more than 30 seconds.
   */
  private function reconnectDelayMs(?int $serverRetryMs): int
  {
    return min($serverRetryMs ?? 1000, 30000);
  }

  /**
   * Sleeps for `$totalMs`, in slices no longer than 50ms, checking `$abortSignal` between each one
   * so a caller's abort ends the wait promptly instead of running out the full delay.
   *
   * @return bool true when the signal fired during the wait, or reads true once the wait has run
   *   out — a final check, so an abort arriving in the last slice still ends iteration rather than
   *   spending a reconnect. The caller ends iteration cleanly on true either way.
   */
  private function waitUnlessAborted(int $totalMs, ?callable $abortSignal): bool
  {
    if ($abortSignal === null) {
      usleep(max(0, $totalMs) * 1000);
      return false;
    }

    $remaining = $totalMs;
    while ($remaining > 0) {
      if ($abortSignal()) {
        return true;
      }
      $slice = min(50, $remaining);
      usleep($slice * 1000);
      $remaining -= $slice;
    }

    return (bool) $abortSignal();
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
    string $metadataClass,
    array $resolvedConfig = [],
    ?bool $declaredSse = null
  ): \Generator {
    $baseUrl = $this->getBaseUrl($resolvedConfig);
    $options['stream'] = true;
    $mergedOptions = $this->applyConfig($options, $resolvedConfig);
    try {
      $response = $this->client->request($method, $baseUrl . $uri, $mergedOptions);
    } catch (\GuzzleHttp\Exception\ConnectException $e) {
      if (strpos($e->getMessage(), 'cURL error 28') !== false) {
        throw new TimeoutException($e->getMessage(), previous: $e);
      }
      throw new CelitechException(message: $e->getMessage(), previous: $e);
    } catch (\GuzzleHttp\Exception\RequestException $e) {
      $this->wrapRequestException($e);
    }

    // Extract metadata once for all chunks
    $metadata = new $metadataClass(
      headers: $this->parseHeaders($response->getHeaders()),
      statusCode: $response->getStatusCode()
    );

    $body = $response->getBody();
    // Declared format wins over the media type; see sendStreamingRequest above.
    $contentType = $response->getHeaderLine('Content-Type');
    $isEventStream = $declaredSse ?? strpos($contentType, 'text/event-stream') !== false;

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
          yield new $responseWrapperClass(
            data: $deserializedData,
            raw: $response,
            metadata: $metadata
          );
        }
      }
    }

    if ($isEventStream) {
      return;
    }

    foreach ($lineDecoder->flush() as $line) {
      yield new $responseWrapperClass(
        data: $deserializer($line),
        raw: $response,
        metadata: $metadata
      );
    }
  }

  /**
   * Wrap a Guzzle RequestException in CelitechApiException.
   *
   * Extracts the HTTP status code, response body, and headers from the response
   * (if available) and throws a new CelitechApiException with the original exception chained.
   * For connection errors where no response exists, status code 0 is used.
   *
   * @param \GuzzleHttp\Exception\RequestException $e The original Guzzle exception
   * @return never
   * @throws CelitechApiException Always thrown with details from the original exception
   */
  private function wrapRequestException(\GuzzleHttp\Exception\RequestException $e): never
  {
    $response = $e->getResponse();
    if ($response) {
      throw new CelitechApiException(
        message: $e->getMessage(),
        statusCode: $response->getStatusCode(),
        responseBody: (string) $response->getBody(),
        responseHeaders: $response->getHeaders(),
        previous: $e
      );
    }
    throw new CelitechException(message: $e->getMessage(), previous: $e);
  }

  /**
   * Decode a JSON response body, tolerating empty and non-JSON bodies.
   *
   * @param string $json The JSON string to decode
   * @return mixed The decoded data, or an empty array for an empty or
   *               non-JSON body
   */
  protected function decodeJson(string $json): mixed
  {
    // Empty / whitespace-only bodies — 204 No Content, 200 with empty body,
    // or any operation whose successful response simply doesn't carry one —
    // are valid responses, not malformed JSON. Return an empty array so the
    // generated `Model::fromArray()` call sites (strictly typed `array
    // $data`) keep working with their default field values, instead of
    // surfacing `JsonException: Syntax error` thrown deep inside the SDK.
    if (trim($json) === '') {
      return [];
    }
    try {
      return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    } catch (\JsonException $e) {
      // The body wasn't valid JSON — a text/plain or HTML body served
      // against a JSON-typed operation, or a malformed payload. Crashing
      // the caller with a low-level JsonException from deep inside the
      // SDK is never useful, so degrade to an empty array (same contract
      // as the empty-body case above) and let the generated
      // `Model::fromArray()` / array-deserialization call sites fall
      // back to their default field values.
      return [];
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
    $this->baseUrl = rtrim($url, '/');
  }

  /**
   * Set the request timeout in seconds, the unit Fern uses for `timeout`.
   *
   * Updates the HTTP client with a new timeout value. This affects all subsequent
   * requests made through this service instance.
   *
   * @param float $timeout Timeout in seconds
   * @return void
   */
  public function setTimeout(float $timeout): void
  {
    $this->client = new Client([
      'handler' => $this->stack,
      'timeout' => $timeout
    ]);
  }

  /**
   * Set service-level configuration that applies to all methods in this service.
   *
   * Supported keys: 'baseUrl', 'timeout', 'retryConfig'
   *
   * @param array $config Configuration overrides
   * @return $this
   */
  public function setConfig(array $config): static
  {
    $this->serviceConfig = $config;
    return $this;
  }

  /**
   * Resolve configuration from the hierarchy: requestConfig > methodConfig > serviceConfig > defaults.
   *
   * @param array|null $methodConfig Method-level configuration override
   * @param array|null $requestConfig Request-level configuration override
   * @return array Merged configuration with all overrides applied
   */
  protected function getResolvedConfig(
    ?array $methodConfig = null,
    ?array $requestConfig = null
  ): array {
    return array_replace($this->serviceConfig ?? [], $methodConfig ?? [], $requestConfig ?? []);
  }

  /**
   * Apply resolved configuration overrides to request options.
   *
   * @param array $options Request options to modify
   * @param array $resolvedConfig Resolved configuration from hierarchy
   * @return array Modified request options with config applied
   */
  protected function applyConfig(array $options, array $resolvedConfig): array
  {
    if (is_array($options['headers'] ?? null)) {
      $options['headers'] = array_filter($options['headers'], static fn($value) => $value !== null);
    }
    $mergedOptions = array_replace_recursive($this->options, $options);

    if (isset($resolvedConfig['timeout'])) {
      $mergedOptions['timeout'] = $resolvedConfig['timeout'];
    }

    if (isset($resolvedConfig['headers']) && is_array($resolvedConfig['headers'])) {
      $mergedOptions['headers'] = array_replace(
        is_array($mergedOptions['headers'] ?? null) ? $mergedOptions['headers'] : [],
        $resolvedConfig['headers']
      );
    }

    if (isset($resolvedConfig['retryConfig']) && is_array($resolvedConfig['retryConfig'])) {
      $allowedRetryKeys = [
        'isEnabled',
        'maxRetries',
        'baseDelayMs',
        'maxDelayMs',
        'delayJitter',
        'delayMultiplier',
        'retryableStatuses',
        'retryableMethods'
      ];
      $mergedOptions = array_replace(
        $mergedOptions,
        array_intersect_key($resolvedConfig['retryConfig'], array_flip($allowedRetryKeys))
      );
    }

    if (isset($mergedOptions['query']) && is_array($mergedOptions['query'])) {
      $mergedOptions['query'] = $this->buildQueryString(
        $this->normalizeQueryParams($mergedOptions['query'])
      );
    }

    return $mergedOptions;
  }

  /**
   * Normalize query parameter values for the wire.
   *
   * OpenAPI `boolean` query parameters must serialize as the literal strings
   * `true`/`false`. PHP (via Guzzle's default query builder) would otherwise
   * render them as `1`/`0`, which strict servers reject with a 422. Recurses
   * into array values so arrays of booleans are normalized too.
   *
   * @param array $query
   * @return array
   */
  private function normalizeQueryParams(array $query): array
  {
    foreach ($query as $key => $value) {
      if (is_bool($value)) {
        $query[$key] = $value ? 'true' : 'false';
      } elseif (is_array($value)) {
        $query[$key] = $this->normalizeQueryParams($value);
      }
    }
    return $query;
  }

  /**
   * Serialize query parameters using the OpenAPI default (style=form,
   * explode=true).
   *
   * A list value repeats its bare key (`tags=a&tags=b`). Handing the array to
   * the HTTP client instead yields PHP's bracket-indexed form
   * (`tags[0]=a&tags[1]=b`), which servers either ignore or reject. Nested
   * and string-keyed values keep bracket notation, which is what deepObject
   * parameters expect. Null values are dropped, matching the previous
   * behaviour.
   *
   * @param array $query
   * @return string
   */
  private function buildQueryString(array $query): string
  {
    $parts = [];
    foreach ($query as $key => $value) {
      $this->appendQueryParts($parts, (string) $key, $value);
    }
    return implode('&', $parts);
  }

  /**
   * Append the encoded `key=value` pairs for a single query parameter.
   *
   * @param array $parts
   * @param string $key
   * @param mixed $value
   * @return void
   */
  private function appendQueryParts(array &$parts, string $key, mixed $value): void
  {
    if ($value === null) {
      return;
    }

    if (is_array($value)) {
      if (array_is_list($value)) {
        foreach ($value as $item) {
          $this->appendQueryParts($parts, $key, $item);
        }
        return;
      }

      foreach ($value as $subKey => $subValue) {
        $this->appendQueryParts($parts, $key . '[' . $subKey . ']', $subValue);
      }
      return;
    }

    $parts[] = rawurlencode($key) . '=' . rawurlencode((string) $value);
  }

  /**
   * Get the base URL for a request, considering config overrides.
   *
   * @param array $resolvedConfig Resolved configuration from hierarchy
   * @return string The base URL to use
   */
  protected function getBaseUrl(array $resolvedConfig): string
  {
    return rtrim($resolvedConfig['baseUrl'] ?? $this->baseUrl, '/');
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
