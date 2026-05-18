<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class History implements \JsonSerializable
{
  /**
   * The status of the eSIM at a given time, possible values are 'RELEASED', 'DOWNLOADED', 'INSTALLED', 'ENABLED', 'DELETED', or 'ERROR'
   */
  #[SerializedName('status')]
  public string $status;

  /**
   * The date when the eSIM status changed in the format 'yyyy-MM-ddThh:mm:ssZZ'
   */
  #[SerializedName('statusDate')]
  public string $statusDate;

  /**
   * Epoch value representing the date when the eSIM status changed
   */
  #[SerializedName('date')]
  public ?float $date;

  /** @var array<string, true> Tracks which fields were explicitly set */
  private array $_dirtyFields = [];

  public function __construct(string $status, string $statusDate, ?float $date = null)
  {
    $this->status = $status;
    $this->statusDate = $statusDate;
    $this->date = $date;

    $this->_dirtyFields = [
      'status' => true,
      'statusDate' => true
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
      status: $data['status'],
      statusDate: $data['statusDate'],
      date: $data['date'] ?? null
    );
    $instance->_dirtyFields = [];
    foreach (['status', 'statusDate', 'date'] as $field) {
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
    $result['status'] = $this->status;
    $result['statusDate'] = $this->statusDate;
    if (array_key_exists('date', $this->_dirtyFields)) {
      $result['date'] = $this->date;
    }
    return $result;
  }

  public function validate(): void
  {
  }
}
