<?php

declare(strict_types=1);

namespace Celitech\Core\Client;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RetryDecoratingClient implements ClientInterface
{
  private const RETRY_STATUS_CODES = [408, 429];
  private const MAX_RETRY_DELAY = 60000; // 60 seconds in milliseconds
  private const JITTER_FACTOR = 0.2; // 20% random jitter

  private ClientInterface $client;
  private int $maxRetries;
  private int $baseDelay;

  /** @var callable(int): void */
  private $sleepFunction;

  /**
   * @param ClientInterface $client
   * @param int $maxRetries
   * @param int $baseDelay Base delay in milliseconds
   * @param ?callable(int): void $sleepFunction Custom sleep function (receives microseconds), defaults to usleep
   */
  public function __construct(
    ClientInterface $client,
    int $maxRetries = 2,
    int $baseDelay = 1000,
    ?callable $sleepFunction = null
  ) {
    $this->client = $client;
    $this->maxRetries = $maxRetries;
    $this->baseDelay = $baseDelay;
    $this->sleepFunction = $sleepFunction ?? 'usleep';
  }

  /**
   * @param RequestInterface $request
   * @return ResponseInterface
   * @throws ClientExceptionInterface
   */
  public function sendRequest(RequestInterface $request): ResponseInterface
  {
    return $this->send($request);
  }

  /**
   * Sends a request with optional per-request timeout, retry and streaming overrides.
   *
   * When a Guzzle or Symfony PSR-18 client is detected — including a Guzzle
   * client reached through php-http/guzzle7-adapter's wrapper — the timeout
   * is forwarded via the client's native API. For other PSR-18 clients the
   * timeout value is silently ignored and every such call is warned about.
   *
   * `$stream` asks for an UNBUFFERED response body — see `doSend()` for which transports honor
   * it and how the others degrade.
   *
   * @param RequestInterface $request
   * @param ?float $timeout Timeout in seconds, or null to use the client default.
   * @param ?int $maxRetries Maximum retry attempts, or null to use the client default.
   * @param ?array{
   *     isEnabled?: bool,
   *     retryableStatuses?: list<int>,
   *     retryableMethods?: list<string>,
   *     baseDelayMs?: int,
   *     delayMultiplier?: int|float,
   *     maxDelayMs?: int,
   *     delayJitter?: int,
   * } $policy Per-call `retryConfig` knobs, with the same meaning as the Guzzle path's `Retry`
   *     middleware so one SDK retries alike on either transport. Null keeps every default.
   * @param bool $stream Whether the caller (an SSE/streaming read) wants frames as they arrive
   *     rather than one fully-materialised body. False for every ordinary JSON/multipart/raw
   *     request, which this leaves byte-for-byte unaffected.
   * @return ResponseInterface
   * @throws ClientExceptionInterface
   */
  public function send(
    RequestInterface $request,
    ?float $timeout = null,
    ?int $maxRetries = null,
    ?array $policy = null,
    bool $stream = false
  ): ResponseInterface {
    $maxRetries = ($policy['isEnabled'] ?? true) === false ? 0 : $maxRetries ?? $this->maxRetries;
    $retryAttempt = 0;
    $lastResponse = null;

    while (true) {
      try {
        $lastResponse = $this->doSend($request, $timeout, $stream);
        if (!$this->shouldRetry($retryAttempt, $maxRetries, $request, $lastResponse, $policy)) {
          return $lastResponse;
        }
      } catch (ClientExceptionInterface $e) {
        // DIVERGES FROM FERN: Fern leaves $lastResponse set, so a transport failure that
        // follows a 429 is delayed by the PREVIOUS response's Retry-After — up to 60s for
        // an attempt that carried no such instruction.
        $lastResponse = null;
        if ($retryAttempt >= $maxRetries || !$this->methodIsRetryable($request, $policy)) {
          throw $e;
        }
      }

      $retryAttempt++;
      $delay = $this->getRetryDelay($retryAttempt, $lastResponse, $policy);
      ($this->sleepFunction)($delay * 1000); // Convert milliseconds to microseconds

      // Rewind the request body so retries don't send an empty body.
      //
      // DIVERGES FROM FERN: PSR-7 requires rewind() to throw on a non-seekable stream, so
      // Fern's unguarded call turns a retryable 503 into a RuntimeException escaping send().
      // A non-seekable body cannot be replayed, so the only honest move is to surface the
      // response we already have rather than re-send a partially consumed one.
      $body = $request->getBody();
      if (!$body->isSeekable()) {
        if ($lastResponse !== null) {
          return $lastResponse;
        }
        throw new \RuntimeException(
          'Request body is not seekable and cannot be replayed for a retry.'
        );
      }
      $body->rewind();
    }
  }

  /**
   * Dispatches the request to the underlying client, forwarding the timeout option and a
   * streaming request to Guzzle or Symfony when available.
   *
   * PSR-18's `ClientInterface::sendRequest()` takes no options at all, so there is no standard
   * way to ask for an unbuffered body — the same gap `$timeout` already works around below. This
   * mirrors that mechanism for `$stream`:
   *
   *  - Guzzle (`GuzzleHttp\ClientInterface`): `stream => true` is Guzzle's own documented way to
   *    get a body that is read progressively off the socket instead of fully downloaded before
   *    `send()` returns. Forwarded alongside `timeout` on the same call.
   *  - Symfony (`Psr18Client`): already streams lazily under the PSR-18 bridge — reading the
   *    PSR-7 body pulls more data from the underlying response on demand, with nothing extra to
   *    request. `$stream` therefore does not change which branch runs here; it only joins
   *    `$timeout` in deciding whether this client-specific branch runs at all.
   *  - Any other PSR-18 client: there is no portable way to request streaming, so the call
   *    degrades to exactly what every request already does — a fully-buffered body, readable
   *    only once `sendRequest()` returns. Correctness over a nice-to-have: the call still
   *    succeeds, just without incremental delivery. A caller who needs frames to arrive before
   *    the connection closes needs a Guzzle or Symfony client.
   *
   * @param RequestInterface $request
   * @param ?float $timeout
   * @param bool $stream
   * @return ResponseInterface
   * @throws ClientExceptionInterface
   */
  private function doSend(
    RequestInterface $request,
    ?float $timeout,
    bool $stream = false
  ): ResponseInterface {
    if ($timeout === null && !$stream) {
      return $this->client->sendRequest($request);
    }

    $guzzleClient = $this->resolveGuzzleClient($this->client);
    if ($guzzleClient !== null) {
      // DIVERGES FROM FERN: `http_errors => false` is required, not optional. Guzzle's
      // send() throws on 4xx/5xx by default, unlike the sendRequest() path below, so with a
      // timeout or stream set Fern's version (a) defeats its own retry-on-5xx, which
      // inspects the returned response, and (b) violates PSR-18, which says an error
      // response must be returned rather than thrown. `allow_redirects => false` likewise
      // matches what Guzzle's own sendRequest() forces, so whether a 3xx is followed does not
      // depend on whether a timeout happens to be set.
      $options = ['http_errors' => false, 'allow_redirects' => false];
      if ($timeout !== null) {
        $options['timeout'] = $timeout;
      }
      if ($stream) {
        $options['stream'] = true;
      }
      return $guzzleClient->send($request, $options);
    }
    if (
      class_exists('Symfony\Component\HttpClient\Psr18Client') &&
      $this->client instanceof \Symfony\Component\HttpClient\Psr18Client
    ) {
      // No streaming option to set: Symfony's PSR-18 bridge already reads the body lazily,
      // so `$stream` needs nothing beyond having reached this branch.
      $client = $this->client;
      if ($timeout !== null) {
        /** @var ClientInterface $client */
        $client = $client->withOptions(['timeout' => $timeout]);
      }
      return $client->sendRequest($request);
    }

    // Streaming has no portable PSR-18 signal to warn through here (unlike timeout below, it
    // is not a value this transport silently drops — there is nothing to pass at all), so an
    // unsupported `$stream` degrades quietly to the buffered read every request already does.
    if ($timeout === null) {
      return $this->client->sendRequest($request);
    }

    // An explicitly requested timeout that this client cannot honour is reported every time
    // it happens, not just the first: in a long-running worker a once-per-process warning
    // means every call after the first silently ignores a timeout the caller asked for.
    trigger_error(
      'Timeout option is not supported for the current PSR-18 client (' .
        get_class($this->client) .
        '). Use Guzzle or Symfony HttpClient for timeout support.',
      E_USER_WARNING
    );
    return $this->client->sendRequest($request);
  }

  /**
   * Finds the GuzzleHttp\ClientInterface a per-call timeout can be forwarded to: either $client
   * itself, or — for php-http/guzzle7-adapter's `Http\Adapter\Guzzle7\Client` — the Guzzle
   * client it wraps.
   *
   * The adapter (verified against php-http/guzzle7-adapter 1.1.0's source) exposes no public
   * accessor for that inner client, only a private `guzzle` property, so reflection is the only
   * way to reach it. Any failure here — the property renamed, or holding something other than a
   * GuzzleHttp\ClientInterface — falls back to null rather than guessing, leaving the client
   * unsupported.
   *
   * The `?\GuzzleHttp\ClientInterface` return type names an optional dependency on purpose: PHP
   * resolves a return type only when a value of it is actually returned, and every path that
   * reaches a `return $client`/`return $inner` has already passed an `interface_exists` guard.
   * On an SDK with no Guzzle installed this method returns null and the interface is never
   * loaded, so do not "fix" this into a hard `use GuzzleHttp\ClientInterface;` import.
   */
  private function resolveGuzzleClient(ClientInterface $client): ?\GuzzleHttp\ClientInterface
  {
    if (
      interface_exists('GuzzleHttp\ClientInterface') &&
      $client instanceof \GuzzleHttp\ClientInterface
    ) {
      return $client;
    }

    if (
      class_exists('Http\Adapter\Guzzle7\Client') &&
      $client instanceof \Http\Adapter\Guzzle7\Client
    ) {
      try {
        $inner = (new \ReflectionProperty($client, 'guzzle'))->getValue($client);
      } catch (\ReflectionException) {
        return null;
      }
      if (
        interface_exists('GuzzleHttp\ClientInterface') &&
        $inner instanceof \GuzzleHttp\ClientInterface
      ) {
        return $inner;
      }
    }

    return null;
  }

  /**
   * @param ?array<string, mixed> $policy
   */
  private function shouldRetry(
    int $retryAttempt,
    int $maxRetries,
    RequestInterface $request,
    ?ResponseInterface $response = null,
    ?array $policy = null
  ): bool {
    if (
      $retryAttempt >= $maxRetries ||
      $response === null ||
      !$this->methodIsRetryable($request, $policy)
    ) {
      return false;
    }

    // A custom list replaces 408/429 and, as on the Guzzle path, every 5xx stays retryable.
    $status = $response->getStatusCode();
    return $status >= 500 ||
      in_array($status, $policy['retryableStatuses'] ?? self::RETRY_STATUS_CODES, true);
  }

  /**
   * @param ?array<string, mixed> $policy
   */
  private function methodIsRetryable(RequestInterface $request, ?array $policy): bool
  {
    return !isset($policy['retryableMethods']) ||
      in_array($request->getMethod(), $policy['retryableMethods'], true);
  }

  /**
   * Calculate the retry delay based on response headers or exponential backoff.
   *
   * @param int $retryAttempt
   * @param ?ResponseInterface $response
   * @param ?array<string, mixed> $policy
   * @return int milliseconds
   */
  private function getRetryDelay(
    int $retryAttempt,
    ?ResponseInterface $response,
    ?array $policy = null
  ): int {
    if ($response !== null) {
      // Check Retry-After header
      $retryAfter = $response->getHeaderLine('Retry-After');
      if ($retryAfter !== '') {
        // delay-seconds is 1*DIGIT (RFC 9110 10.2.3); "-5" or "1e3" is no hint.
        if (ctype_digit($retryAfter)) {
          $retryAfterSeconds = (int) $retryAfter;
          if ($retryAfterSeconds > 0) {
            return min($retryAfterSeconds * 1000, self::MAX_RETRY_DELAY);
          }
        } else {
          // Only an IMF-fixdate: strtotime() would also accept "-5" or "tomorrow". The
          // round-trip rejects an out-of-range date ("99 Jan") that createFromFormat()
          // would otherwise roll over into a valid one.
          $retryAfterDate = \DateTimeImmutable::createFromFormat(
            '!D, d M Y H:i:s \G\M\T',
            $retryAfter,
            new \DateTimeZone('UTC')
          );
          if (
            $retryAfterDate !== false &&
            $retryAfterDate->format('D, d M Y H:i:s \G\M\T') === $retryAfter
          ) {
            $delay = ($retryAfterDate->getTimestamp() - time()) * 1000;
            if ($delay > 0) {
              return min($delay, self::MAX_RETRY_DELAY);
            }
          }
        }
      }

      // Check X-RateLimit-Reset header
      $rateLimitReset = $response->getHeaderLine('X-RateLimit-Reset');
      if ($rateLimitReset !== '' && is_numeric($rateLimitReset)) {
        $resetTime = (int) $rateLimitReset;
        $delay = $resetTime * 1000 - (int) (microtime(true) * 1000);
        if ($delay > 0) {
          return $this->addPositiveJitter(min($delay, self::MAX_RETRY_DELAY));
        }
      }
    }

    // base * multiplier^(n-1), capped at maxDelayMs. Each knob falls back to this client's own
    // default independently, so with none set this is exactly the historical 2^(n-1) * base,
    // capped at 60s. `delayJitter` swaps the ±10% jitter for the Guzzle path's 0..delayJitter.
    $delay = (int) min(
      ($policy['baseDelayMs'] ?? $this->baseDelay) *
        ($policy['delayMultiplier'] ?? 2) ** ($retryAttempt - 1),
      $policy['maxDelayMs'] ?? self::MAX_RETRY_DELAY
    );
    return isset($policy['delayJitter'])
      ? $delay + mt_rand(0, max(0, (int) $policy['delayJitter']))
      : $this->addSymmetricJitter($delay);
  }

  /**
   * Add positive jitter (0% to +20%) to the delay.
   *
   * @param int $delay
   * @return int
   */
  private function addPositiveJitter(int $delay): int
  {
    $jitterMultiplier = 1 + (mt_rand() / mt_getrandmax()) * self::JITTER_FACTOR;
    return (int) ($delay * $jitterMultiplier);
  }

  /**
   * Add symmetric jitter (-10% to +10%) to the delay.
   *
   * @param int $delay
   * @return int
   */
  private function addSymmetricJitter(int $delay): int
  {
    $jitterMultiplier = 1 + (mt_rand() / mt_getrandmax() - 0.5) * self::JITTER_FACTOR;
    return (int) ($delay * $jitterMultiplier);
  }
}
