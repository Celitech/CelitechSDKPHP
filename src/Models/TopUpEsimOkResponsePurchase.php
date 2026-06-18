<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class TopUpEsimOkResponsePurchase implements \JsonSerializable
{
  /**
   * ID of the purchase
   */
  #[SerializedName('id')]
  public string $id;

  /**
   * ID of the package
   */
  #[SerializedName('packageId')]
  public string $packageId;

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

  /** @var array<string, true> Tracks which fields were explicitly set */
  private array $_dirtyFields = [];

  public function __construct(
    string $id,
    string $packageId,
    string $createdDate,
    ?string $startDate = null,
    ?string $endDate = null,
    ?float $startTime = null,
    ?float $endTime = null
  ) {
    $this->id = $id;
    $this->packageId = $packageId;
    $this->startDate = $startDate;
    $this->endDate = $endDate;
    $this->createdDate = $createdDate;
    $this->startTime = $startTime;
    $this->endTime = $endTime;

    $this->_dirtyFields = [
      'id' => true,
      'packageId' => true,
      'startDate' => true,
      'endDate' => true,
      'createdDate' => true
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
      id: $data['id'],
      packageId: $data['packageId'],
      startDate: $data['startDate'],
      endDate: $data['endDate'],
      createdDate: $data['createdDate'],
      startTime: $data['startTime'] ?? null,
      endTime: $data['endTime'] ?? null
    );
    $instance->_dirtyFields = [];
    foreach (
      ['id', 'packageId', 'startDate', 'endDate', 'createdDate', 'startTime', 'endTime']
      as $field
    ) {
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
    $result['id'] = $this->id;
    $result['packageId'] = $this->packageId;
    $result['startDate'] = $this->startDate;
    $result['endDate'] = $this->endDate;
    $result['createdDate'] = $this->createdDate;
    if (array_key_exists('startTime', $this->_dirtyFields)) {
      $result['startTime'] = $this->startTime;
    }
    if (array_key_exists('endTime', $this->_dirtyFields)) {
      $result['endTime'] = $this->endTime;
    }
    return $result;
  }

  public function toMultipart(): array
  {
    return [
      [
        'name' => 'id',
        'contents' => $this->id
      ],

      [
        'name' => 'packageId',
        'contents' => $this->packageId
      ],

      [
        'name' => 'startDate',
        'contents' => $this->startDate
      ],

      [
        'name' => 'endDate',
        'contents' => $this->endDate
      ],

      [
        'name' => 'createdDate',
        'contents' => $this->createdDate
      ],

      [
        'name' => 'startTime',
        'contents' => $this->startTime
      ],

      [
        'name' => 'endTime',
        'contents' => $this->endTime
      ]
    ];
  }

  public function validate(): void
  {
  }
}
