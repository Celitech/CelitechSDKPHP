<?php

declare(strict_types=1);

namespace Celitech\IFrame\Types;

use Celitech\Core\Json\JsonSerializableType;
use Celitech\Core\Json\ReflectiveSerialization;
use Celitech\Core\Json\JsonProperty;

/**
 * The reflective deserializer reconstructs this model with `new static($values)`, so a subclass has
 * to keep a constructor it can call with one associative array.
 *
 * @phpstan-consistent-constructor
 */
class TokenResponse extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * The generated token
   */
  #[JsonProperty('token', required: true)]
  public string $token;

  /**
   * @param array{
   *   token: string,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->token = $values['token'];

    $this->_dirtyFields = [
      'token' => true
    ];
  }

  public function toMultipart(): array
  {
    $parts = [];
    $parts[] = [
      'name' => 'token',
      'contents' => $this->token
    ];

    return $parts;
  }

  public function validate(): void
  {
  }
}
