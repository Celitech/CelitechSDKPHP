<?php

declare(strict_types=1);

namespace Celitech\Destinations\Types;

use Celitech\Core\Json\JsonSerializableType;
use Celitech\Core\Json\ReflectiveSerialization;
use Celitech\Core\Json\JsonProperty;
use Celitech\Core\Types\ArrayType;

/**
 * The reflective deserializer reconstructs this model with `new static($values)`, so a subclass has
 * to keep a constructor it can call with one associative array.
 *
 * @phpstan-consistent-constructor
 */
class ListDestinationsResponse extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * @var \Celitech\Destinations\Types\ListDestinationsResponseDestinationsItem[]
   */
  #[
    JsonProperty('destinations', required: true),
    ArrayType([\Celitech\Destinations\Types\ListDestinationsResponseDestinationsItem::class])
  ]
  public array $destinations;

  /**
   * @param array{
   *   destinations: \Celitech\Destinations\Types\ListDestinationsResponseDestinationsItem[],
   * } $values
   */
  public function __construct(array $values)
  {
    $this->destinations = $values['destinations'];

    $this->_dirtyFields = [
      'destinations' => true
    ];
  }

  public function toMultipart(): array
  {
    $parts = [];
    $parts[] = [
      'name' => 'destinations',
      'contents' => json_encode($this->destinations),
      'headers' => ['Content-Type' => 'application/json']
    ];

    return $parts;
  }

  public function validate(): void
  {
    foreach ($this->destinations as $item) {
      $item->validate();
    }
  }
}
