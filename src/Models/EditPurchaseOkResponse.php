<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class EditPurchaseOkResponse implements \JsonSerializable
{
  /**
   * ID of the purchase
   */
  #[SerializedName('purchaseId')]
  public string $purchaseId;

  /**
   * Start date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ'
   */
  #[SerializedName('newStartDate')]
  public ?string $newStartDate;

  /**
   * End date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ'
   */
  #[SerializedName('newEndDate')]
  public ?string $newEndDate;

  /**
   * Epoch value representing the new start time of the package's validity
   */
  #[SerializedName('newStartTime')]
  public ?float $newStartTime;

  /**
   * Epoch value representing the new end time of the package's validity
   */
  #[SerializedName('newEndTime')]
  public ?float $newEndTime;

  /** @var array<string, true> Tracks which fields were explicitly set */
  private array $_dirtyFields = [];

  public function __construct(
    string $purchaseId,
    ?string $newStartDate = null,
    ?string $newEndDate = null,
    ?float $newStartTime = null,
    ?float $newEndTime = null
  ) {
    $this->purchaseId = $purchaseId;
    $this->newStartDate = $newStartDate;
    $this->newEndDate = $newEndDate;
    $this->newStartTime = $newStartTime;
    $this->newEndTime = $newEndTime;

    $this->_dirtyFields = [
      'purchaseId' => true,
      'newStartDate' => true,
      'newEndDate' => true
    ];
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
      newStartDate: $data['newStartDate'],
      newEndDate: $data['newEndDate'],
      newStartTime: $data['newStartTime'] ?? null,
      newEndTime: $data['newEndTime'] ?? null
    );
    $instance->_dirtyFields = [];
    foreach (['purchaseId', 'newStartDate', 'newEndDate', 'newStartTime', 'newEndTime'] as $field) {
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
    $result['newStartDate'] = $this->newStartDate;
    $result['newEndDate'] = $this->newEndDate;
    if (array_key_exists('newStartTime', $this->_dirtyFields)) {
      $result['newStartTime'] = $this->newStartTime;
    }
    if (array_key_exists('newEndTime', $this->_dirtyFields)) {
      $result['newEndTime'] = $this->newEndTime;
    }
    return $result;
  }

  public function validate(): void
  {
  }
}
