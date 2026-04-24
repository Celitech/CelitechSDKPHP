<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CreatePurchaseOkResponsePurchase implements \JsonSerializable
{
  /**
   * ID of the purchase
   */
  #[SerializedName('id')]
  public string $id;

  /**
   * ID of the package
   */
  #[SerializedName('packageId')]
  public string $packageId;

  /**
   * Start date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ'
   */
  #[SerializedName('startDate')]
  public ?string $startDate;

  /**
   * End date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ'
   */
  #[SerializedName('endDate')]
  public ?string $endDate;

  /**
   * Creation date of the purchase in the format 'yyyy-MM-ddThh:mm:ssZZ'
   */
  #[SerializedName('createdDate')]
  public string $createdDate;

  /**
   * Epoch value representing the start time of the package's validity
   */
  #[SerializedName('startTime')]
  public ?float $startTime;

  /**
   * Epoch value representing the end time of the package's validity
   */
  #[SerializedName('endTime')]
  public ?float $endTime;

  public function __construct(
    string $id,
    string $packageId,
    string $createdDate,
    ?string $startDate = null,
    ?string $endDate = null,
    ?float $startTime = null,
    ?float $endTime = null
  ) {
    $this->id = $id;
    $this->packageId = $packageId;
    $this->startDate = $startDate;
    $this->endDate = $endDate;
    $this->createdDate = $createdDate;
    $this->startTime = $startTime;
    $this->endTime = $endTime;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    return new self(
      id: $data['id'] ?? null,
      packageId: $data['packageId'] ?? null,
      startDate: $data['startDate'] ?? null,
      endDate: $data['endDate'] ?? null,
      createdDate: $data['createdDate'] ?? null,
      startTime: $data['startTime'] ?? null,
      endTime: $data['endTime'] ?? null
    );
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [
      'id' => $this->id,
      'packageId' => $this->packageId,
      'startDate' => $this->startDate,
      'endDate' => $this->endDate,
      'createdDate' => $this->createdDate,
      'startTime' => $this->startTime,
      'endTime' => $this->endTime
    ];

    foreach (['startTime', 'endTime'] as $optionalKey) {
      if ($result[$optionalKey] === null) {
        unset($result[$optionalKey]);
      }
    }

    return $result;
  }
}
