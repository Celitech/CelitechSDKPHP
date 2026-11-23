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
class GetEsimDeviceResponse extends JsonSerializableType
{
  use ReflectiveSerialization;

  #[JsonProperty('device', required: true)]
  public \Celitech\ESim\Types\GetEsimDeviceResponseDevice $device;

  /**
   * @param array{
   *   device: \Celitech\ESim\Types\GetEsimDeviceResponseDevice,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->device = $values['device'];

    $this->_dirtyFields = [
      'device' => true
    ];
  }

  public function toMultipart(): array
  {
    $parts = [];
    $parts[] = [
      'name' => 'device',
      'contents' => json_encode($this->device),
      'headers' => ['Content-Type' => 'application/json']
    ];

    return $parts;
  }

  public function validate(): void
  {
    $this->device->validate();
  }
}
