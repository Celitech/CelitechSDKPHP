<?php

declare(strict_types=1);

namespace Celitech\Types;

use Celitech\Core\Json\JsonSerializableType;
use Celitech\Core\Json\ReflectiveSerialization;
use Celitech\Core\Json\JsonProperty;

/**
 * The reflective deserializer reconstructs this model with `new static($values)`, so a subclass has
 * to keep a constructor it can call with one associative array.
 *
 * @phpstan-consistent-constructor
 */
class ErrorResponse extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * Message of the error
   */
  #[JsonProperty('message')]
  public ?string $message;

  /**
   * @param array{
   *   message?: ?string,
   * } $values
   */
  public function __construct(array $values = [])
  {
    $this->message = $values['message'] ?? null;

    if (array_key_exists('message', $values)) {
      $this->_dirtyFields['message'] = true;
    }
  }

  public function toMultipart(): array
  {
    $parts = [];
    if ($this->message !== null) {
      $parts[] = [
        'name' => 'message',
        'contents' => $this->message
      ];
    }

    return $parts;
  }

  public function validate(): void
  {
  }
}
