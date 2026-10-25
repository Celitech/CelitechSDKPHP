<?php

declare(strict_types=1);

namespace Celitech\Core\Client;

class RawBodyApiRequest extends BaseApiRequest
{
  /**
   * @param string $baseUrl The base URL for the request
   * @param string $path The path for the request
   * @param HttpMethod $method The HTTP method for the request
   * @param array<string, mixed> $headers Additional headers for the request (optional)
   * @param array<string, mixed> $query Query parameters for the request (optional)
   * @param mixed $body The request body, sent on the wire exactly as given (optional). The
   *     caller is responsible for its own `Content-Type` header, since this request carries no
   *     default one — unlike `JsonApiRequest` or `UrlEncodedApiRequest`.
   */
  public function __construct(
    string $baseUrl,
    string $path,
    HttpMethod $method,
    array $headers = [],
    array $query = [],
    public readonly mixed $body = null
  ) {
    parent::__construct($baseUrl, $path, $method, $headers, $query);
  }
}
