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
class CreatePurchaseResponsePurchase extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * ID of the purchase
   */
  #[JsonProperty('id', required: true)]
  public string $id;

  /**
   * ID of the package
   */
  #[JsonProperty('packageId', required: true)]
  public string $packageId;

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
   * @param array{
   *   id: string,
   *   packageId: string,
   *   startDate: ?DateTime,
   *   endDate: ?DateTime,
   *   createdDate: DateTime,
   *   startTime?: ?float,
   *   endTime?: ?float,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->id = $values['id'];
    $this->packageId = $values['packageId'];
    $this->startDate = $values['startDate'];
    $this->endDate = $values['endDate'];
    $this->createdDate = $values['createdDate'];
    $this->startTime = $values['startTime'] ?? null;
    $this->endTime = $values['endTime'] ?? null;

    $this->_dirtyFields = [
      'id' => true,
      'packageId' => true,
      'startDate' => true,
      'endDate' => true,
      'createdDate' => true
    ];
    if (array_key_exists('startTime', $values)) {
      $this->_dirtyFields['startTime'] = true;
    }
    if (array_key_exists('endTime', $values)) {
      $this->_dirtyFields['endTime'] = true;
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
      'name' => 'packageId',
      'contents' => $this->packageId
    ];

    $parts[] = [
      'name' => 'startDate',
      'contents' => $this->startDate
    ];

    $parts[] = [
      'name' => 'endDate',
      'contents' => $this->endDate
    ];

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

    return $parts;
  }

  public function validate(): void
  {
  }
}
