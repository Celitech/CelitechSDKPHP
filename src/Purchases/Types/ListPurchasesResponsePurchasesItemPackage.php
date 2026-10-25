<?php

declare(strict_types=1);

namespace Celitech\Purchases\Types;

use Celitech\Core\Json\JsonSerializableType;
use Celitech\Core\Json\ReflectiveSerialization;
use Celitech\Core\Json\JsonProperty;

/**
 * The reflective deserializer reconstructs this model with `new static($values)`, so a subclass has
 * to keep a constructor it can call with one associative array.
 *
 * @phpstan-consistent-constructor
 */
class ListPurchasesResponsePurchasesItemPackage extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * ID of the package
   */
  #[JsonProperty('id', required: true)]
  public string $id;

  /**
   * Size of the package in Bytes. A value of `-1` indicates an unlimited package.
   */
  #[JsonProperty('dataLimitInBytes', required: true)]
  public float $dataLimitInBytes;

  /**
   * Size of the package in GB. A value of `-1` indicates an unlimited (date-based) package.
   */
  #[JsonProperty('dataLimitInGB', required: true)]
  public float $dataLimitInGb;

  /**
   * ISO3 representation of the package's destination.
   */
  #[JsonProperty('destination', required: true)]
  public string $destination;

  /**
   * ISO2 representation of the package's destination.
   */
  #[JsonProperty('destinationISO2', required: true)]
  public string $destinationIso2;

  /**
   * Name of the package's destination
   */
  #[JsonProperty('destinationName', required: true)]
  public string $destinationName;

  /**
   * Price of the package in cents
   */
  #[JsonProperty('priceInCents', required: true)]
  public float $priceInCents;

  /**
   * @param array{
   *   id: string,
   *   dataLimitInBytes: float,
   *   dataLimitInGb: float,
   *   destination: string,
   *   destinationIso2: string,
   *   destinationName: string,
   *   priceInCents: float,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->id = $values['id'];
    $this->dataLimitInBytes = $values['dataLimitInBytes'];
    $this->dataLimitInGb = $values['dataLimitInGb'];
    $this->destination = $values['destination'];
    $this->destinationIso2 = $values['destinationIso2'];
    $this->destinationName = $values['destinationName'];
    $this->priceInCents = $values['priceInCents'];

    $this->_dirtyFields = [
      'id' => true,
      'dataLimitInBytes' => true,
      'dataLimitInGB' => true,
      'destination' => true,
      'destinationISO2' => true,
      'destinationName' => true,
      'priceInCents' => true
    ];
  }

  public function toMultipart(): array
  {
    $parts = [];
    $parts[] = [
      'name' => 'id',
      'contents' => $this->id
    ];

    $parts[] = [
      'name' => 'dataLimitInBytes',
      'contents' => (string) $this->dataLimitInBytes
    ];

    $parts[] = [
      'name' => 'dataLimitInGB',
      'contents' => (string) $this->dataLimitInGb
    ];

    $parts[] = [
      'name' => 'destination',
      'contents' => $this->destination
    ];

    $parts[] = [
      'name' => 'destinationISO2',
      'contents' => $this->destinationIso2
    ];

    $parts[] = [
      'name' => 'destinationName',
      'contents' => $this->destinationName
    ];

    $parts[] = [
      'name' => 'priceInCents',
      'contents' => (string) $this->priceInCents
    ];

    return $parts;
  }

  public function validate(): void
  {
  }
}
