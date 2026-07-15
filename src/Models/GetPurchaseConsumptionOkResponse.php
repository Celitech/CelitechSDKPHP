<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class GetPurchaseConsumptionOkResponse implements \JsonSerializable
{
  /**
   * Remaining balance of the package in bytes. Returns `-1` for unlimited packages.
   */
  #[SerializedName('dataUsageRemainingInBytes')]
  public float $dataUsageRemainingInBytes;

  /**
   * Remaining balance of the package in GB. Returns `-1` for unlimited packages.
   */
  #[SerializedName('dataUsageRemainingInGB')]
  public float $dataUsageRemainingInGb;

  /**
   * Status of the connectivity, possible values are 'ACTIVE' or 'NOT_ACTIVE'
   */
  #[SerializedName('status')]
  public string $status;

  public function __construct(
    float $dataUsageRemainingInBytes,
    float $dataUsageRemainingInGb,
    string $status
  ) {
    $this->dataUsageRemainingInBytes = $dataUsageRemainingInBytes;
    $this->dataUsageRemainingInGb = $dataUsageRemainingInGb;
    $this->status = $status;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    $instance = new self(
      dataUsageRemainingInBytes: $data['dataUsageRemainingInBytes'],
      dataUsageRemainingInGb: $data['dataUsageRemainingInGB'],
      status: $data['status']
    );

    return $instance;
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [];
    $result['dataUsageRemainingInBytes'] = $this->dataUsageRemainingInBytes;
    $result['dataUsageRemainingInGB'] = $this->dataUsageRemainingInGb;
    $result['status'] = $this->status;
    return $result;
  }

  public function toMultipart(): array
  {
    return [
      [
        'name' => 'dataUsageRemainingInBytes',
        'contents' => (string) $this->dataUsageRemainingInBytes
      ],

      [
        'name' => 'dataUsageRemainingInGb',
        'contents' => (string) $this->dataUsageRemainingInGb
      ],

      [
        'name' => 'status',
        'contents' => $this->status
      ]
    ];
  }

  public function validate(): void
  {
  }
}
