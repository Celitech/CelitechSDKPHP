<?php

declare(strict_types=1);

namespace Celitech\Purchases\Requests;

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
class EditPurchaseRequest extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * Optional. A unique value (for example a UUID) that makes this request safe to retry. If a request with the same `Idempotency-Key` and the same body succeeds, repeating it returns the original response instead of performing the action again, and the response includes the header `Idempotent-Replayed: true`. Keys are scoped to your credentials and remembered for 3 days. Only successful (2xx) responses are remembered, so a request that failed can be retried with the same key. Reusing a key with a different request returns `422`; sending it while the original request is still being processed returns `409`. If the idempotency store is temporarily unavailable, the request is not processed and returns `503`; retry it. Must be 1 to 255 printable ASCII characters, without spaces.
   */
  public ?string $idempotencyKey;

  /**
   * ID of the purchase
   */
  #[JsonProperty('purchaseId', required: true)]
  public string $purchaseId;

  /**
   * Start date of the package's validity in the format 'yyyy-MM-dd'. This date can be set to the current day or any day within the next 12 months.
   */
  #[JsonProperty('startDate', required: true), Date(Date::TYPE_DATE)]
  public DateTime $startDate;

  /**
   * End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date.
   */
  #[JsonProperty('endDate', required: true), Date(Date::TYPE_DATE)]
  public DateTime $endDate;

  /**
   * Epoch value representing the start time of the package's validity. This timestamp can be set to the current time or any time within the next 12 months.
   */
  #[JsonProperty('startTime')]
  public ?float $startTime;

  /**
   * Epoch value representing the end time of the package's validity. End time can be maximum 90 days after Start time.
   */
  #[JsonProperty('endTime')]
  public ?float $endTime;

  /**
   * @param array{
   *   purchaseId: string,
   *   startDate: DateTime,
   *   endDate: DateTime,
   *   idempotencyKey?: ?string,
   *   startTime?: ?float,
   *   endTime?: ?float,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->idempotencyKey = $values['idempotencyKey'] ?? null;
    $this->purchaseId = $values['purchaseId'];
    $this->startDate = $values['startDate'];
    $this->endDate = $values['endDate'];
    $this->startTime = $values['startTime'] ?? null;
    $this->endTime = $values['endTime'] ?? null;

    $this->_dirtyFields = [
      'purchaseId' => true,
      'startDate' => true,
      'endDate' => true
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
      'name' => 'purchaseId',
      'contents' => $this->purchaseId
    ];

    $parts[] = [
      'name' => 'startDate',
      'contents' => $this->startDate
    ];

    $parts[] = [
      'name' => 'endDate',
      'contents' => $this->endDate
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
