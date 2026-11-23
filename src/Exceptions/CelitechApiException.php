<?php

declare(strict_types=1);

namespace Celitech\Exceptions;

use Celitech\Core\Client\ErrorResponseHeaders;
use Throwable;

/**
 * Exception thrown when an API request returns an error response.
 *
 * Contains the HTTP status code, error message, response body, and response headers.
 * This exception wraps HTTP client errors to provide a consistent error handling
 * interface across all SDK service methods.
 *
 * @property-read array<string, string[]> $responseHeaders The response headers
 */
class CelitechApiException extends CelitechException
{
  private mixed $body;

  /**
   * The raw response body, as a string.
   */
  public readonly string $responseBody;

  /**
   * Create a new CelitechApiException instance.
   *
   * The parameters, and their names, are those of a Fern-generated API exception, so a class
   * written against Fern's can replace this one and still be thrown by every service method.
   *
   * @param string $message The error message
   * @param int $statusCode The HTTP status code
   * @param mixed $body The response body: the raw string, as Fern passes it
   * @param ?Throwable $previous The previous exception for chaining
   */
  public function __construct(
    string $message,
    public readonly int $statusCode,
    mixed $body,
    ?Throwable $previous = null
  ) {
    parent::__construct($message, $statusCode, $previous);
    $this->body = $body;
    $this->responseBody = match (true) {
      is_string($body) => $body,
      $body === null => '',
      default => json_encode($body) ?: ''
    };
  }

  /**
   * @return int The HTTP status code
   */
  public function getStatusCode(): int
  {
    return $this->statusCode;
  }

  /**
   * The response body as Fern-generated SDKs return it: the raw string, or an empty string when
   * there was none. Use getDecodedBody() for the decoded JSON. Declared `mixed` like Fern's, so a
   * customer subclass overriding it still loads.
   *
   * @return mixed The response body
   */
  public function getBody(): mixed
  {
    return $this->body;
  }

  /**
   * Try to decode the response body as JSON.
   *
   * Returns the decoded data as an associative array, or null if the response
   * body is empty or not valid JSON.
   *
   * @return mixed The decoded JSON data or null
   */
  public function getDecodedBody(): mixed
  {
    if ($this->responseBody === '') {
      return null;
    }
    $decoded = json_decode($this->responseBody, true);
    return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
  }

  /**
   * Reads `responseHeaders`, which `ErrorResponseHeaders` holds rather than this class, so that
   * a replacement for this class written against Fern's constructor receives them too.
   */
  public function __get(string $name): mixed
  {
    if ($name === 'responseHeaders') {
      return ErrorResponseHeaders::of($this);
    }
    if (property_exists($this, $name)) {
      throw new \Error('Cannot access private property ' . static::class . '::$' . $name);
    }
    trigger_error('Undefined property: ' . static::class . '::$' . $name, E_USER_WARNING);
    return null;
  }

  public function __isset(string $name): bool
  {
    return $name === 'responseHeaders';
  }
}
