<?php

declare(strict_types=1);

namespace Celitech\ESim\Types;

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
class GetEsimHistoryResponseEsimHistoryItem extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * The status of the eSIM at a given time, possible values are 'RELEASED', 'DOWNLOADED', 'INSTALLED', 'ENABLED', 'DELETED', or 'ERROR'
   */
  #[JsonProperty('status', required: true)]
  public string $status;

  /**
   * The date when the eSIM status changed in the format 'yyyy-MM-ddThh:mm:ssZZ'
   */
  #[JsonProperty('statusDate', required: true), Date(Date::TYPE_DATETIME)]
  public DateTime $statusDate;

  /**
   * Epoch value representing the date when the eSIM status changed
   */
  #[JsonProperty('date')]
  public ?float $date;

  /**
   * @param array{
   *   status: string,
   *   statusDate: DateTime,
   *   date?: ?float,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->status = $values['status'];
    $this->statusDate = $values['statusDate'];
    $this->date = $values['date'] ?? null;

    $this->_dirtyFields = [
      'status' => true,
      'statusDate' => true
    ];
    if (array_key_exists('date', $values)) {
      $this->_dirtyFields['date'] = true;
    }
  }

  public function toMultipart(): array
  {
    $parts = [];
    $parts[] = [
      'name' => 'status',
      'contents' => $this->status
    ];

    $parts[] = [
      'name' => 'statusDate',
      'contents' => $this->statusDate
    ];

    if ($this->date !== null) {
      $parts[] = [
        'name' => 'date',
        'contents' => (string) $this->date
      ];
    }

    return $parts;
  }

  public function validate(): void
  {
  }
}
