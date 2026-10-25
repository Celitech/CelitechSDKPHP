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
class EditPurchaseResponse extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * ID of the purchase
   */
  #[JsonProperty('purchaseId', required: true)]
  public string $purchaseId;

  /**
   * Start date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ'
   */
  #[JsonProperty('newStartDate', required: true), Date(Date::TYPE_DATETIME)]
  public ?DateTime $newStartDate;

  /**
   * End date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ'
   */
  #[JsonProperty('newEndDate', required: true), Date(Date::TYPE_DATETIME)]
  public ?DateTime $newEndDate;

  /**
   * Epoch value representing the new start time of the package's validity
   */
  #[JsonProperty('newStartTime')]
  public ?float $newStartTime;

  /**
   * Epoch value representing the new end time of the package's validity
   */
  #[JsonProperty('newEndTime')]
  public ?float $newEndTime;

  /**
   * @param array{
   *   purchaseId: string,
   *   newStartDate: ?DateTime,
   *   newEndDate: ?DateTime,
   *   newStartTime?: ?float,
   *   newEndTime?: ?float,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->purchaseId = $values['purchaseId'];
    $this->newStartDate = $values['newStartDate'];
    $this->newEndDate = $values['newEndDate'];
    $this->newStartTime = $values['newStartTime'] ?? null;
    $this->newEndTime = $values['newEndTime'] ?? null;

    $this->_dirtyFields = [
      'purchaseId' => true,
      'newStartDate' => true,
      'newEndDate' => true
    ];
    if (array_key_exists('newStartTime', $values)) {
      $this->_dirtyFields['newStartTime'] = true;
    }
    if (array_key_exists('newEndTime', $values)) {
      $this->_dirtyFields['newEndTime'] = true;
    }
  }

  public function toMultipart(): array
  {
    $parts = [];
    $parts[] = [
      'name' => 'purchaseId',
      'contents' => $this->purchaseId
    ];

    $parts[] = [
      'name' => 'newStartDate',
      'contents' => $this->newStartDate
    ];

    $parts[] = [
      'name' => 'newEndDate',
      'contents' => $this->newEndDate
    ];

    if ($this->newStartTime !== null) {
      $parts[] = [
        'name' => 'newStartTime',
        'contents' => (string) $this->newStartTime
      ];
    }

    if ($this->newEndTime !== null) {
      $parts[] = [
        'name' => 'newEndTime',
        'contents' => (string) $this->newEndTime
      ];
    }

    return $parts;
  }

  public function validate(): void
  {
  }
}
