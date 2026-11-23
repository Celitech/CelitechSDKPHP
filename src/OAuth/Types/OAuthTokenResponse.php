<?php

declare(strict_types=1);

namespace Celitech\OAuth\Types;

use Celitech\Core\Json\JsonSerializableType;
use Celitech\Core\Json\ReflectiveSerialization;
use Celitech\Core\Json\JsonProperty;

/**
 * The reflective deserializer reconstructs this model with `new static($values)`, so a subclass has
 * to keep a constructor it can call with one associative array.
 *
 * @phpstan-consistent-constructor
 */
class OAuthTokenResponse extends JsonSerializableType
{
  use ReflectiveSerialization;

  #[JsonProperty('access_token')]
  public ?string $accessToken;

  #[JsonProperty('expires_in')]
  public ?int $expiresIn;

  /**
   * @param array{
   *   accessToken?: ?string,
   *   expiresIn?: ?int,
   * } $values
   */
  public function __construct(array $values = [])
  {
    $this->accessToken = $values['accessToken'] ?? null;
    $this->expiresIn = $values['expiresIn'] ?? null;

    if (array_key_exists('accessToken', $values)) {
      $this->_dirtyFields['access_token'] = true;
    }
    if (array_key_exists('expiresIn', $values)) {
      $this->_dirtyFields['expires_in'] = true;
    }
  }

  public function toMultipart(): array
  {
    $parts = [];
    if ($this->accessToken !== null) {
      $parts[] = [
        'name' => 'access_token',
        'contents' => $this->accessToken
      ];
    }

    if ($this->expiresIn !== null) {
      $parts[] = [
        'name' => 'expires_in',
        'contents' => (string) $this->expiresIn
      ];
    }

    return $parts;
  }

  public function validate(): void
  {
  }
}
