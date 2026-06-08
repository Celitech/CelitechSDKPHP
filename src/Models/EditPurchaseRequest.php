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

  /** @var array<string, true> Tracks which fields were explicitly set */
  private array $_dirtyFields = [];

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

    $this->_dirtyFields = [
      'purchaseId' => true,
      'startDate' => true,
      'endDate' => true
    ];
    if ($startTime !== null) {
      $this->_dirtyFields['startTime'] = true;
    }
    if ($endTime !== null) {
      $this->_dirtyFields['endTime'] = true;
    }
  }

  /**
   * Mark one or more optional fields as explicitly set so they are
   * included in {@see jsonSerialize()} output.
   *
   * Constructor-created objects automatically track required fields and
   * any optional field passed with a non-default value. Use this method
   * to force-include a field that was left at its default (e.g. explicit null):
   *
   *     $pet = new Pet(name: 'Buddy');
   *     $pet->setFields('tag'); // tag (null) will now appear in JSON
   *
   * Objects created via {@see fromArray()} already track every field
   * present in the input data, so setFields() is not needed for them.
   *
   * @param string ...$fields JSON field names (original API names) to mark as set
   * @return static
   */
  public function setFields(string ...$fields): static
  {
    foreach ($fields as $field) {
      $this->_dirtyFields[$field] = true;
    }
    return $this;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    $instance = new self(
      purchaseId: $data['purchaseId'],
      startDate: $data['startDate'],
      endDate: $data['endDate'],
      startTime: $data['startTime'] ?? null,
      endTime: $data['endTime'] ?? null
    );
    $instance->_dirtyFields = [];
    foreach (['purchaseId', 'startDate', 'endDate', 'startTime', 'endTime'] as $field) {
      if (array_key_exists($field, $data)) {
        $instance->_dirtyFields[$field] = true;
      }
    }
    return $instance;
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [];
    $result['purchaseId'] = $this->purchaseId;
    $result['startDate'] = $this->startDate;
    $result['endDate'] = $this->endDate;
    if (array_key_exists('startTime', $this->_dirtyFields)) {
      $result['startTime'] = $this->startTime;
    }
    if (array_key_exists('endTime', $this->_dirtyFields)) {
      $result['endTime'] = $this->endTime;
    }
    return $result;
  }

  public function validate(): void
  {
  }
}
