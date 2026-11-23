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
class GetEsimDeviceResponseDevice extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * Name of the OEM
   */
  #[JsonProperty('oem', required: true)]
  public string $oem;

  /**
   * Name of the Device
   */
  #[JsonProperty('hardwareName', required: true)]
  public string $hardwareName;

  /**
   * Model of the Device
   */
  #[JsonProperty('hardwareModel', required: true)]
  public string $hardwareModel;

  /**
   * Serial Number of the eSIM
   */
  #[JsonProperty('eid', required: true)]
  public string $eid;

  /**
   * @param array{
   *   oem: string,
   *   hardwareName: string,
   *   hardwareModel: string,
   *   eid: string,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->oem = $values['oem'];
    $this->hardwareName = $values['hardwareName'];
    $this->hardwareModel = $values['hardwareModel'];
    $this->eid = $values['eid'];

    $this->_dirtyFields = [
      'oem' => true,
      'hardwareName' => true,
      'hardwareModel' => true,
      'eid' => true
    ];
  }

  public function toMultipart(): array
  {
    $parts = [];
    $parts[] = [
      'name' => 'oem',
      'contents' => $this->oem
    ];

    $parts[] = [
      'name' => 'hardwareName',
      'contents' => $this->hardwareName
    ];

    $parts[] = [
      'name' => 'hardwareModel',
      'contents' => $this->hardwareModel
    ];

    $parts[] = [
      'name' => 'eid',
      'contents' => $this->eid
    ];

    return $parts;
  }

  public function validate(): void
  {
  }
}
