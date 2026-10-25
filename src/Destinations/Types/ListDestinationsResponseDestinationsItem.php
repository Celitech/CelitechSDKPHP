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
class ListDestinationsResponseDestinationsItem extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * Name of the destination
   */
  #[JsonProperty('name', required: true)]
  public string $name;

  /**
   * ISO3 representation of the destination
   */
  #[JsonProperty('destination', required: true)]
  public string $destination;

  /**
   * ISO2 representation of the destination
   */
  #[JsonProperty('destinationISO2', required: true)]
  public string $destinationIso2;

  /**
   * @var string[]
   * This array indicates the geographical area covered by a specific destination. If the destination represents a single country, the array will include that country. However, if the destination represents a broader regional scope, the array will be populated with the names of the countries belonging to that region.
   */
  #[JsonProperty('supportedCountries', required: true), ArrayType(['string'])]
  public array $supportedCountries;

  /**
   * @param array{
   *   name: string,
   *   destination: string,
   *   destinationIso2: string,
   *   supportedCountries: string[],
   * } $values
   */
  public function __construct(array $values)
  {
    $this->name = $values['name'];
    $this->destination = $values['destination'];
    $this->destinationIso2 = $values['destinationIso2'];
    $this->supportedCountries = $values['supportedCountries'];

    $this->_dirtyFields = [
      'name' => true,
      'destination' => true,
      'destinationISO2' => true,
      'supportedCountries' => true
    ];
  }

  public function toMultipart(): array
  {
    $parts = [];
    $parts[] = [
      'name' => 'name',
      'contents' => $this->name
    ];

    $parts[] = [
      'name' => 'destination',
      'contents' => $this->destination
    ];

    $parts[] = [
      'name' => 'destinationISO2',
      'contents' => $this->destinationIso2
    ];

    foreach ($this->supportedCountries as $item) {
      $parts[] = [
        'name' => 'supportedCountries',
        'contents' => $item
      ];
    }

    return $parts;
  }

  public function validate(): void
  {
  }
}
