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
class GetPurchaseConsumptionResponse extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * Remaining balance of the package in bytes. Returns `-1` for unlimited packages.
   */
  #[JsonProperty('dataUsageRemainingInBytes', required: true)]
  public float $dataUsageRemainingInBytes;

  /**
   * Remaining balance of the package in GB. Returns `-1` for unlimited packages.
   */
  #[JsonProperty('dataUsageRemainingInGB', required: true)]
  public float $dataUsageRemainingInGb;

  /**
   * Status of the connectivity, possible values are 'ACTIVE' or 'NOT_ACTIVE'
   */
  #[JsonProperty('status', required: true)]
  public string $status;

  /**
   * @param array{
   *   dataUsageRemainingInBytes: float,
   *   dataUsageRemainingInGb: float,
   *   status: string,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->dataUsageRemainingInBytes = $values['dataUsageRemainingInBytes'];
    $this->dataUsageRemainingInGb = $values['dataUsageRemainingInGb'];
    $this->status = $values['status'];

    $this->_dirtyFields = [
      'dataUsageRemainingInBytes' => true,
      'dataUsageRemainingInGB' => true,
      'status' => true
    ];
  }

  public function toMultipart(): array
  {
    $parts = [];
    $parts[] = [
      'name' => 'dataUsageRemainingInBytes',
      'contents' => (string) $this->dataUsageRemainingInBytes
    ];

    $parts[] = [
      'name' => 'dataUsageRemainingInGB',
      'contents' => (string) $this->dataUsageRemainingInGb
    ];

    $parts[] = [
      'name' => 'status',
      'contents' => $this->status
    ];

    return $parts;
  }

  public function validate(): void
  {
  }
}
