<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class TopUpESimRequest implements \JsonSerializable
{
  #[SerializedName('iccid')]
  public ?string $iccid;

  #[SerializedName('dataLimitInGB')]
  public ?float $dataLimitInGb;

  #[SerializedName('startDate')]
  public ?string $startDate;

  #[SerializedName('endDate')]
  public ?string $endDate;

  #[SerializedName('duration')]
  public ?float $duration;

  #[SerializedName('email')]
  public ?string $email;

  #[SerializedName('referenceId')]
  public ?string $referenceId;

  #[SerializedName('emailBrand')]
  public ?string $emailBrand;

  #[SerializedName('startTime')]
  public ?float $startTime;

  #[SerializedName('endTime')]
  public ?float $endTime;

  /** @var array<string, true> Tracks which fields were explicitly set */
  private array $_dirtyFields = [];

  public function __construct(
    ?string $iccid = null,
    ?float $dataLimitInGb = null,
    ?string $startDate = null,
    ?string $endDate = null,
    ?float $duration = null,
    ?string $email = null,
    ?string $referenceId = null,
    ?string $emailBrand = null,
    ?float $startTime = null,
    ?float $endTime = null
  ) {
    $this->iccid = $iccid;
    $this->dataLimitInGb = $dataLimitInGb;
    $this->startDate = $startDate;
    $this->endDate = $endDate;
    $this->duration = $duration;
    $this->email = $email;
    $this->referenceId = $referenceId;
    $this->emailBrand = $emailBrand;
    $this->startTime = $startTime;
    $this->endTime = $endTime;
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
      iccid: $data['iccid'] ?? null,
      dataLimitInGb: $data['dataLimitInGB'] ?? null,
      startDate: $data['startDate'] ?? null,
      endDate: $data['endDate'] ?? null,
      duration: $data['duration'] ?? null,
      email: $data['email'] ?? null,
      referenceId: $data['referenceId'] ?? null,
      emailBrand: $data['emailBrand'] ?? null,
      startTime: $data['startTime'] ?? null,
      endTime: $data['endTime'] ?? null
    );
    $instance->_dirtyFields = [];
    foreach (
      [
        'iccid',
        'dataLimitInGB',
        'startDate',
        'endDate',
        'duration',
        'email',
        'referenceId',
        'emailBrand',
        'startTime',
        'endTime'
      ]
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
    if (array_key_exists('iccid', $this->_dirtyFields)) {
      $result['iccid'] = $this->iccid;
    }
    if (array_key_exists('dataLimitInGB', $this->_dirtyFields)) {
      $result['dataLimitInGB'] = $this->dataLimitInGb;
    }
    if (array_key_exists('startDate', $this->_dirtyFields)) {
      $result['startDate'] = $this->startDate;
    }
    if (array_key_exists('endDate', $this->_dirtyFields)) {
      $result['endDate'] = $this->endDate;
    }
    if (array_key_exists('duration', $this->_dirtyFields)) {
      $result['duration'] = $this->duration;
    }
    if (array_key_exists('email', $this->_dirtyFields)) {
      $result['email'] = $this->email;
    }
    if (array_key_exists('referenceId', $this->_dirtyFields)) {
      $result['referenceId'] = $this->referenceId;
    }
    if (array_key_exists('emailBrand', $this->_dirtyFields)) {
      $result['emailBrand'] = $this->emailBrand;
    }
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
