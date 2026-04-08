<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class EditPurchaseRequest implements \JsonSerializable
{
  /**
   * ID of the purchase
   */
  #[SerializedName('purchaseId')]
  public string $purchaseId;

  /**
   * Start date of the package's validity in the format 'yyyy-MM-dd'. This date can be set to the current day or any day within the next 12 months.
   */
  #[SerializedName('startDate')]
  public string $startDate;

  /**
   * End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date.
   */
  #[SerializedName('endDate')]
  public string $endDate;

  /**
   * Epoch value representing the start time of the package's validity. This timestamp can be set to the current time or any time within the next 12 months.
   */
  #[SerializedName('startTime')]
  public ?float $startTime;

  /**
   * Epoch value representing the end time of the package's validity. End time can be maximum 90 days after Start time.
   */
  #[SerializedName('endTime')]
  public ?float $endTime;

  public function __construct(
    string $purchaseId,
    string $startDate,
    string $endDate,
    ?float $startTime = null,
    ?float $endTime = null
  ) {
    $this->purchaseId = $purchaseId;
    $this->startDate = $startDate;
    $this->endDate = $endDate;
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
      purchaseId: $data['purchaseId'] ?? null,
      startDate: $data['startDate'] ?? null,
      endDate: $data['endDate'] ?? null,
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
      'purchaseId' => $this->purchaseId,
      'startDate' => $this->startDate,
      'endDate' => $this->endDate,
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
