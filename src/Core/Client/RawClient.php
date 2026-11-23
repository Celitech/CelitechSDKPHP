<?php

declare(strict_types=1);

namespace Celitech\Core\Client;

use JsonSerializable;
use InvalidArgumentException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Http\Message\MultipartStream\MultipartStreamBuilder;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Celitech\Core\Json\JsonApiRequest;
use Celitech\Core\Json\JsonEncoder;
use Celitech\Core\Multipart\MultipartApiRequest;

class RawClient
{
  /**
   * @var RetryDecoratingClient $client
   */
  private RetryDecoratingClient $client;

  /**
   * @var RequestFactoryInterface $requestFactory
   */
  private RequestFactoryInterface $requestFactory;

  /**
   * @var StreamFactoryInterface $streamFactory
   */
  private StreamFactoryInterface $streamFactory;

  /**
   * @var array<string, string> $headers
   */
  private array $headers;

  /**
   * @var ?(callable(): array<string, string>) $getAuthHeaders
   */
  private $getAuthHeaders;

  /**
   * @param ?array{
   *   baseUrl?: string,
   *   client?: ClientInterface,
   *   maxRetries?: int,
   *   timeout?: float,
   *   headers?: array<string, mixed>,
   *   getAuthHeaders?: callable(): array<string, string>,
   * } $options
   */
  public function __construct(public readonly ?array $options = null)
  {
    $this->client = HttpClientBuilder::build(
      $this->options['client'] ?? null,
      $this->options['maxRetries'] ?? 2
    );
    $this->requestFactory = HttpClientBuilder::requestFactory();
    $this->streamFactory = HttpClientBuilder::streamFactory();
    $this->headers = $this->options['headers'] ?? [];
    $this->getAuthHeaders = $this->options['getAuthHeaders'] ?? null;
  }

  /**
   * @param BaseApiRequest $request
   * @param ?array{
   *     maxRetries?: int,
   *     retry?: array<string, mixed>,
   *     timeout?: float,
   *     stream?: bool,
   *     headers?: array<string, mixed>,
   *     queryParameters?: array<string, mixed>,
   *     bodyProperties?: array<string, mixed>,
   * } $options `stream`, when true, asks the underlying transport for an unbuffered response
   *     body (see `RetryDecoratingClient::doSend` for which transports honor it); every caller
   *     outside an SSE/streaming read leaves it unset, which keeps this byte-for-byte identical
   *     to before.
   * @return ResponseInterface
   * @throws ClientExceptionInterface
   */
  public function sendRequest(BaseApiRequest $request, ?array $options = null): ResponseInterface
  {
    $opts = $options ?? [];
    $httpRequest = $this->buildRequest($request, $opts);

    $timeout = $opts['timeout'] ?? ($this->options['timeout'] ?? null);
    $maxRetries = $opts['maxRetries'] ?? null;

    return $this->client->send(
      $httpRequest,
      $timeout,
      $maxRetries,
      $opts['retry'] ?? null,
      $opts['stream'] ?? false
    );
  }

  /**
   * @param BaseApiRequest $request
   * @param array{
   *     headers?: array<string, mixed>,
   *     queryParameters?: array<string, mixed>,
   *     bodyProperties?: array<string, mixed>,
   * } $options
   * @return RequestInterface
   */
  private function buildRequest(BaseApiRequest $request, array $options): RequestInterface
  {
    $url = $this->buildUrl($request, $options);
    $headers = $this->encodeHeaders($request, $options);

    $httpRequest = $this->requestFactory->createRequest($request->method->name, $url);

    // Encode body and, for multipart, capture the Content-Type with boundary.
    if ($request instanceof MultipartApiRequest && $request->body !== null) {
      $builder = new MultipartStreamBuilder($this->streamFactory);
      $request->body->addToBuilder($builder);
      $httpRequest = $httpRequest->withBody($builder->build());
      $headers['Content-Type'] = "multipart/form-data; boundary={$builder->getBoundary()}";
    } else {
      $body = $this->encodeRequestBody($request, $options);
      if ($body !== null) {
        $httpRequest = $httpRequest->withBody($body);
      }
    }

    // DIVERGES FROM FERN: Fern hands every value straight to withHeader(), but PSR-7 rejects a
    // null with "Header values must be RFC 7230 compatible strings". Every optional header
    // parameter this generator emits is passed unconditionally, so an unset one would throw
    // instead of being omitted. Guzzle silently drops nulls, so skipping them is also what
    // keeps this path wire-identical to the handler-stack one during the migration.
    foreach ($headers as $name => $value) {
      if ($value === null) {
        continue;
      }
      // Same scalar-coercion rules the query encoder needs, hence one shared helper rather
      // than two that drift. Deliberately NOT newline-stripping: a value carrying CR/LF is a
      // header-injection attempt, and PSR-7 rejecting it is the correct outcome, not a bug to
      // work around. A list is an exploded array header (`style: simple`, explode=true) and
      // goes out as one header line per element — as Fern's RawClient sends any list it is
      // handed, including one in the caller's own `headers` option.
      $httpRequest = $httpRequest->withHeader(
        $name,
        is_array($value) && array_is_list($value)
          ? array_map(fn($v) => $this->stringifyValue($v), $value)
          : $this->stringifyValue($value)
      );
    }

    return $httpRequest;
  }

  /**
   * @param BaseApiRequest $request
   * @param array{
   *     headers?: array<string, mixed>,
   * } $options
   * @return array<string, mixed> Values may be null; buildRequest drops those.
   */
  private function encodeHeaders(BaseApiRequest $request, array $options): array
  {
    $authHeaders = $this->getAuthHeaders !== null ? ($this->getAuthHeaders)() : [];
    // DIVERGES FROM FERN: Fern matches on get_class($request), an exact-class test, while
    // encodeRequestBody below dispatches on instanceof. A consumer subclassing JsonApiRequest
    // therefore got its body encoded but fell through to the default arm for headers. Matching
    // on instanceof in both places is what makes the two agree.
    return match (true) {
      $request instanceof JsonApiRequest => array_merge(
        [
          'Content-Type' => 'application/json',
          'Accept' => '*/*'
        ],
        $this->headers,
        $authHeaders,
        $request->headers,
        $options['headers'] ?? []
      ),
      $request instanceof UrlEncodedApiRequest => array_merge(
        [
          'Content-Type' => 'application/x-www-form-urlencoded',
          'Accept' => '*/*'
        ],
        $this->headers,
        $authHeaders,
        $request->headers,
        $options['headers'] ?? []
      ),
      $request instanceof MultipartApiRequest => array_merge(
        $this->headers,
        $authHeaders,
        $request->headers,
        $options['headers'] ?? []
      ),
      // No default Content-Type: a raw body's caller sets its own (text/plain,
      // application/octet-stream, a vendor +json suffix, application/xml, ...), and this
      // request type exists precisely for the bodies those defaults would get wrong.
      $request instanceof RawBodyApiRequest => array_merge(
        $this->headers,
        $authHeaders,
        $request->headers,
        $options['headers'] ?? []
      ),
      default => throw new InvalidArgumentException(
        'Unsupported request type: ' . get_class($request)
      )
    };
  }

  /**
   * @param BaseApiRequest $request
   * @param array{
   *     bodyProperties?: array<string, mixed>,
   * } $options
   * @return ?StreamInterface
   */
  private function encodeRequestBody(BaseApiRequest $request, array $options): ?StreamInterface
  {
    if ($request instanceof JsonApiRequest) {
      return $request->body === null
        ? null
        : $this->streamFactory->createStream(
          JsonEncoder::encode($this->buildJsonBody($request->body, $options))
        );
    }

    if ($request instanceof UrlEncodedApiRequest) {
      if ($request->body === null) {
        return null;
      }
      $body = $this->buildJsonBody($request->body, $options);
      if ($body instanceof JsonSerializable) {
        $body = $body->jsonSerialize();
      }
      if (is_object($body)) {
        $body = (array) $body;
      }
      if (!is_array($body)) {
        throw new InvalidArgumentException(
          'URL-encoded request bodies must serialize to an array.'
        );
      }
      return $this->streamFactory->createStream(http_build_query($body));
    }

    if ($request instanceof MultipartApiRequest) {
      return null;
    }

    if ($request instanceof RawBodyApiRequest) {
      $body = $request->body;
      return match (true) {
        $body === null => null,
        $body instanceof StreamInterface => $body,
        is_resource($body) => $this->streamFactory->createStreamFromResource($body),
        is_string($body), $body instanceof \Stringable => $this->streamFactory->createStream(
          (string) $body
        ),
        default => throw new InvalidArgumentException(
          'Unsupported raw request body type: ' . get_debug_type($body)
        )
      };
    }

    throw new InvalidArgumentException('Unsupported request type: ' . get_class($request));
  }

  /**
   * @param mixed $body
   * @param array{
   *     bodyProperties?: array<string, mixed>,
   * } $options
   * @return mixed
   */
  private function buildJsonBody(mixed $body, array $options): mixed
  {
    $overrideProperties = $options['bodyProperties'] ?? [];
    if (is_array($body) && (empty($body) || self::isSequential($body))) {
      return array_merge($body, $overrideProperties);
    }

    if ($body instanceof JsonSerializable) {
      $result = $body->jsonSerialize();
    } else {
      $result = $body;
    }
    if (is_array($result)) {
      $result = array_merge($result, $overrideProperties);
      if (empty($result)) {
        // force to be serialized as {} instead of []
        return (object) $result;
      }
    }

    return $result;
  }

  /**
   * Percent-encodes a value for use inside a single URL path segment. Encoding happens here,
   * where the value is still separate from the path template, so that a value containing "/"
   * or ".." cannot change which endpoint the request resolves to.
   */
  public static function encodePathParam(
    mixed $value,
    string $style = 'simple',
    bool $explode = false,
    string $name = ''
  ): string {
    // A list is an array path parameter: each element encoded on its own, then joined per its
    // OpenAPI style. Anything else that is not a scalar keeps the JSON fallback.
    if (is_array($value) && array_is_list($value)) {
      $values = array_map(fn($v) => self::encodePathParam($v), $value);
    } elseif (is_bool($value)) {
      $values = [$value ? 'true' : 'false'];
    } elseif (is_null($value)) {
      $values = [''];
    } elseif (is_scalar($value) || (is_object($value) && method_exists($value, '__toString'))) {
      $values = [self::encodeSegment((string) $value)];
    } else {
      $values = [self::encodeSegment(JsonEncoder::encode($value))];
    }

    return match ($style) {
      'label' => '.' . implode($explode ? '.' : ',', $values),
      // The name is encoded too: it comes from the spec, and a `/` in it must not re-target
      // the request any more than one in the value may.
      'matrix' => $explode
        ? implode('', array_map(fn($v) => ';' . rawurlencode($name) . "={$v}", $values))
        : ';' . rawurlencode($name) . '=' . implode(',', $values),
      default => implode(',', $values)
    };
  }

  /**
   * A value of exactly "." or ".." is a dot-segment that the transport removes (RFC 3986 5.2.4),
   * re-targeting the request. Percent-encoding it does not help: libcurl >= 8.x removes "%2E%2E"
   * too. So such a value is refused rather than sent.
   */
  private static function encodeSegment(string $value): string
  {
    if ($value === '.' || $value === '..') {
      throw new InvalidArgumentException(
        "Path parameter value '{$value}' is a dot-segment and cannot be sent."
      );
    }
    return rawurlencode($value);
  }

  /**
   * @param BaseApiRequest $request
   * @param array{
   *     queryParameters?: array<string, mixed>,
   * } $options
   * @return string
   */
  private function buildUrl(BaseApiRequest $request, array $options): string
  {
    $baseUrl = $request->baseUrl;
    $trimmedBaseUrl = rtrim($baseUrl, '/');
    $trimmedBasePath = ltrim($request->path, '/');
    $url = "{$trimmedBaseUrl}/{$trimmedBasePath}";
    $query = array_merge($request->query, $options['queryParameters'] ?? []);
    if (!empty($query)) {
      $url .= '?' . $this->encodeQuery($query);
    }
    return $url;
  }

  /**
   * @param array<string, mixed> $query
   * @return string
   */
  private function encodeQuery(array $query): string
  {
    $parts = [];
    foreach ($query as $key => $value) {
      $this->appendQueryParts($parts, (string) $key, $value);
    }
    return implode('&', $parts);
  }

  /**
   * DIVERGES FROM FERN, on three points that are wire bugs rather than style.
   *
   * Fern emits `key=null` for a null value; every optional query parameter this generator emits
   * is passed unconditionally, so an unset one would put the literal string "null" on the wire.
   * Fern also flattens an associative array to a repeated bare key, losing the subkeys a
   * deepObject parameter depends on, and encodes with urlencode (space -> "+") where the rest of
   * this SDK uses rawurlencode (space -> "%20").
   *
   * These semantics are BaseService::appendQueryParts verbatim, so the Guzzle path and this one
   * put identical bytes on the wire during the migration.
   *
   * @param list<string> $parts
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

    $parts[] = rawurlencode($key) . '=' . rawurlencode($this->stringifyValue($value));
  }

  /**
   * Scalar -> string WITHOUT encoding; appendQueryParts does the single rawurlencode.
   *
   * Booleans are spelled out because `(string) false` is "" in PHP. BaseService relies on
   * normalizeQueryParams having already done this before buildQueryString; RawClient has no such
   * pre-pass, so it happens here.
   */
  private function stringifyValue(mixed $value): string
  {
    if (is_bool($value)) {
      return $value ? 'true' : 'false';
    }
    if (is_scalar($value)) {
      return (string) $value;
    }
    if (is_object($value) && method_exists($value, '__toString')) {
      return (string) $value;
    }
    return JsonEncoder::encode($value);
  }

  /**
   * Check if an array is sequential, not associative.
   * @param mixed[] $arr
   * @return bool
   */
  private static function isSequential(array $arr): bool
  {
    if (empty($arr)) {
      return false;
    }
    $length = count($arr);
    $keys = array_keys($arr);
    for ($i = 0; $i < $length; $i++) {
      if ($keys[$i] !== $i) {
        return false;
      }
    }
    return true;
  }
}
