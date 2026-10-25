<?php

declare(strict_types=1);

namespace Celitech\ESim\Types;

use Celitech\Core\Json\JsonSerializableType;
use Celitech\Core\Json\ReflectiveSerialization;
use Celitech\Core\Json\JsonProperty;

/**
 * The reflective deserializer reconstructs this model with `new static($values)`, so a subclass has
 * to keep a constructor it can call with one associative array.
 *
 * @phpstan-consistent-constructor
 */
class SuspendEsimResponse extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * Confirmation message
   */
  #[JsonProperty('message', required: true)]
  public string $message;

  /**
   * @param array{
   *   message: string,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->message = $values['message'];

    $this->_dirtyFields = [
      'message' => true
    ];
  }

  public function toMultipart(): array
  {
    $parts = [];
    $parts[] = [
      'name' => 'message',
      'contents' => $this->message
    ];

    return $parts;
  }

  public function validate(): void
  {
  }
}
