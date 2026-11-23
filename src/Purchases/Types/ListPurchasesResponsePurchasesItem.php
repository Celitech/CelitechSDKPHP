<?php

declare(strict_types=1);

namespace Celitech\Purchases\Types;

use Celitech\Core\Json\JsonSerializableType;
use Celitech\Core\Json\ReflectiveSerialization;
use Celitech\Core\Json\JsonProperty;
use Celitech\Core\Types\Date;
use DateTime;

/**
 * The reflective deserializer reconstructs this model with `new static($values)`, so a subclass has
 * to keep a constructor it can call with one associative array.
 *
 * @phpstan-consistent-constructor
 */
class ListPurchasesResponsePurchasesItem extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * ID of the purchase
   */
  #[JsonProperty('id', required: true)]
  public string $id;

  /**
   * Start date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ'
   */
  #[JsonProperty('startDate', required: true), Date(Date::TYPE_DATETIME)]
  public ?DateTime $startDate;

  /**
   * End date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ'
   */
  #[JsonProperty('endDate', required: true), Date(Date::TYPE_DATETIME)]
  public ?DateTime $endDate;

  /**
   * Duration of the package in days. Possible values are 1, 2, 7, 14, 30, or 90. `null` for unlimited (date-based) packages.
   */
  #[JsonProperty('duration')]
  public ?float $duration;

  /**
   * Creation date of the purchase in the format 'yyyy-MM-ddThh:mm:ssZZ'
   */
  #[JsonProperty('createdDate', required: true), Date(Date::TYPE_DATETIME)]
  public DateTime $createdDate;

  /**
   * Epoch value representing the start time of the package's validity
   */
  #[JsonProperty('startTime')]
  public ?float $startTime;

  /**
   * Epoch value representing the end time of the package's validity
   */
  #[JsonProperty('endTime')]
  public ?float $endTime;

  /**
   * Epoch value representing the date of creation of the purchase
   */
  #[JsonProperty('createdAt')]
  public ?float $createdAt;

  #[JsonProperty('package', required: true)]
  public \Celitech\Purchases\Types\ListPurchasesResponsePurchasesItemPackage $package;

  #[JsonProperty('esim', required: true)]
  public \Celitech\Purchases\Types\ListPurchasesResponsePurchasesItemEsim $esim;

  /**
   * The `source` indicates whether the purchase was made from the API, dashboard, landing-page, promo-page or iframe. For purchases made before September 8, 2023, the value will be displayed as 'Not available'.
   */
  #[JsonProperty('source', required: true)]
  public string $source;

  /**
   * The `purchaseType` indicates whether this is the initial purchase that creates the eSIM (First Purchase) or a subsequent top-up on an existing eSIM (Top-up Purchase).
   */
  #[JsonProperty('purchaseType', required: true)]
  public string $purchaseType;

  /**
   * The `referenceId` that was provided by the partner during the purchase or top-up flow. This identifier can be used for analytics and debugging purposes.
   */
  #[JsonProperty('referenceId')]
  public ?string $referenceId;

  /**
   * @param array{
   *   id: string,
   *   startDate: ?DateTime,
   *   endDate: ?DateTime,
   *   createdDate: DateTime,
   *   package: \Celitech\Purchases\Types\ListPurchasesResponsePurchasesItemPackage,
   *   esim: \Celitech\Purchases\Types\ListPurchasesResponsePurchasesItemEsim,
   *   source: string,
   *   purchaseType: string,
   *   duration?: ?float,
   *   startTime?: ?float,
   *   endTime?: ?float,
   *   createdAt?: ?float,
   *   referenceId?: ?string,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->id = $values['id'];
    $this->startDate = $values['startDate'];
    $this->endDate = $values['endDate'];
    $this->duration = $values['duration'] ?? null;
    $this->createdDate = $values['createdDate'];
    $this->startTime = $values['startTime'] ?? null;
    $this->endTime = $values['endTime'] ?? null;
    $this->createdAt = $values['createdAt'] ?? null;
    $this->package = $values['package'];
    $this->esim = $values['esim'];
    $this->source = $values['source'];
    $this->purchaseType = $values['purchaseType'];
    $this->referenceId = $values['referenceId'] ?? null;

    $this->_dirtyFields = [
      'id' => true,
      'startDate' => true,
      'endDate' => true,
      'createdDate' => true,
      'package' => true,
      'esim' => true,
      'source' => true,
      'purchaseType' => true
    ];
    if (array_key_exists('duration', $values)) {
      $this->_dirtyFields['duration'] = true;
    }
    if (array_key_exists('startTime', $values)) {
      $this->_dirtyFields['startTime'] = true;
    }
    if (array_key_exists('endTime', $values)) {
      $this->_dirtyFields['endTime'] = true;
    }
    if (array_key_exists('createdAt', $values)) {
      $this->_dirtyFields['createdAt'] = true;
    }
    if (array_key_exists('referenceId', $values)) {
      $this->_dirtyFields['referenceId'] = true;
    }
  }

  public function toMultipart(): array
  {
    $parts = [];
    $parts[] = [
      'name' => 'id',
      'contents' => $this->id
    ];

    $parts[] = [
      'name' => 'startDate',
      'contents' => $this->startDate
    ];

    $parts[] = [
      'name' => 'endDate',
      'contents' => $this->endDate
    ];

    if ($this->duration !== null) {
      $parts[] = [
        'name' => 'duration',
        'contents' => (string) $this->duration
      ];
    }

    $parts[] = [
      'name' => 'createdDate',
      'contents' => $this->createdDate
    ];

    if ($this->startTime !== null) {
      $parts[] = [
        'name' => 'startTime',
        'contents' => (string) $this->startTime
      ];
    }

    if ($this->endTime !== null) {
      $parts[] = [
        'name' => 'endTime',
        'contents' => (string) $this->endTime
      ];
    }

    if ($this->createdAt !== null) {
      $parts[] = [
        'name' => 'createdAt',
        'contents' => (string) $this->createdAt
      ];
    }

    $parts[] = [
      'name' => 'package',
      'contents' => json_encode($this->package),
      'headers' => ['Content-Type' => 'application/json']
    ];

    $parts[] = [
      'name' => 'esim',
      'contents' => json_encode($this->esim),
      'headers' => ['Content-Type' => 'application/json']
    ];

    $parts[] = [
      'name' => 'source',
      'contents' => $this->source
    ];

    $parts[] = [
      'name' => 'purchaseType',
      'contents' => $this->purchaseType
    ];

    if ($this->referenceId !== null) {
      $parts[] = [
        'name' => 'referenceId',
        'contents' => $this->referenceId
      ];
    }

    return $parts;
  }

  public function validate(): void
  {
    $this->package->validate();
    $this->esim->validate();
  }
}
