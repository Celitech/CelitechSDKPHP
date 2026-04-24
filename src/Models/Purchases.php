<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class Purchases implements \JsonSerializable
{
  /**
   * ID of the purchase
   */
  #[SerializedName('id')]
  public string $id;

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
   * Duration of the package in days. Possible values are 1, 2, 7, 14, 30, or 90.
   */
  #[SerializedName('duration')]
  public ?float $duration;

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

  /**
   * Epoch value representing the date of creation of the purchase
   */
  #[SerializedName('createdAt')]
  public ?float $createdAt;

  #[SerializedName('package')]
  public Package $package;

  #[SerializedName('esim')]
  public PurchasesEsim $esim;

  /**
   * The `source` indicates whether the purchase was made from the API, dashboard, landing-page, promo-page or iframe. For purchases made before September 8, 2023, the value will be displayed as 'Not available'.
   */
  #[SerializedName('source')]
  public string $source;

  /**
   * The `purchaseType` indicates whether this is the initial purchase that creates the eSIM (First Purchase) or a subsequent top-up on an existing eSIM (Top-up Purchase).
   */
  #[SerializedName('purchaseType')]
  public string $purchaseType;

  /**
   * The `referenceId` that was provided by the partner during the purchase or top-up flow. This identifier can be used for analytics and debugging purposes.
   */
  #[SerializedName('referenceId')]
  public ?string $referenceId;

  public function __construct(
    string $id,
    string $createdDate,
    Package $package,
    PurchasesEsim $esim,
    string $source,
    string $purchaseType,
    ?string $startDate = null,
    ?string $endDate = null,
    ?float $duration = null,
    ?float $startTime = null,
    ?float $endTime = null,
    ?float $createdAt = null,
    ?string $referenceId = null
  ) {
    $this->id = $id;
    $this->startDate = $startDate;
    $this->endDate = $endDate;
    $this->duration = $duration;
    $this->createdDate = $createdDate;
    $this->startTime = $startTime;
    $this->endTime = $endTime;
    $this->createdAt = $createdAt;
    $this->package = $package;
    $this->esim = $esim;
    $this->source = $source;
    $this->purchaseType = $purchaseType;
    $this->referenceId = $referenceId;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    return new self(
      id: $data['id'] ?? null,
      startDate: $data['startDate'] ?? null,
      endDate: $data['endDate'] ?? null,
      duration: $data['duration'] ?? null,
      createdDate: $data['createdDate'] ?? null,
      startTime: $data['startTime'] ?? null,
      endTime: $data['endTime'] ?? null,
      createdAt: $data['createdAt'] ?? null,
      package: isset($data['package']) && is_array($data['package'])
        ? Package::fromArray($data['package'])
        : null,
      esim: isset($data['esim']) && is_array($data['esim'])
        ? PurchasesEsim::fromArray($data['esim'])
        : null,
      source: $data['source'] ?? null,
      purchaseType: $data['purchaseType'] ?? null,
      referenceId: $data['referenceId'] ?? null
    );
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [
      'id' => $this->id,
      'startDate' => $this->startDate,
      'endDate' => $this->endDate,
      'duration' => $this->duration,
      'createdDate' => $this->createdDate,
      'startTime' => $this->startTime,
      'endTime' => $this->endTime,
      'createdAt' => $this->createdAt,
      'package' => $this->package,
      'esim' => $this->esim,
      'source' => $this->source,
      'purchaseType' => $this->purchaseType,
      'referenceId' => $this->referenceId
    ];

    foreach (['duration', 'startTime', 'endTime', 'createdAt', 'referenceId'] as $optionalKey) {
      if ($result[$optionalKey] === null) {
        unset($result[$optionalKey]);
      }
    }

    return $result;
  }
}
