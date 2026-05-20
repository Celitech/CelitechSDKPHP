<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CreatePurchaseRequest implements \JsonSerializable
{
  #[SerializedName('destination')]
  public ?string $destination;

  #[SerializedName('dataLimitInGB')]
  public ?float $dataLimitInGb;

  #[SerializedName('startDate')]
  public ?string $startDate;

  #[SerializedName('endDate')]
  public ?string $endDate;

  #[SerializedName('email')]
  public ?string $email;

  #[SerializedName('referenceId')]
  public ?string $referenceId;

  #[SerializedName('networkBrand')]
  public ?string $networkBrand;

  #[SerializedName('emailBrand')]
  public ?string $emailBrand;

  #[SerializedName('language')]
  public ?string $language;

  #[SerializedName('startTime')]
  public ?float $startTime;

  #[SerializedName('endTime')]
  public ?float $endTime;

  /** @var array<string, true> Tracks which fields were explicitly set */
  private array $_dirtyFields = [];

  public function __construct(
    ?string $destination = null,
    ?float $dataLimitInGb = null,
    ?string $startDate = null,
    ?string $endDate = null,
    ?string $email = null,
    ?string $referenceId = null,
    ?string $networkBrand = null,
    ?string $emailBrand = null,
    ?string $language = null,
    ?float $startTime = null,
    ?float $endTime = null
  ) {
    $this->destination = $destination;
    $this->dataLimitInGb = $dataLimitInGb;
    $this->startDate = $startDate;
    $this->endDate = $endDate;
    $this->email = $email;
    $this->referenceId = $referenceId;
    $this->networkBrand = $networkBrand;
    $this->emailBrand = $emailBrand;
    $this->language = $language;
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
      destination: $data['destination'] ?? null,
      dataLimitInGb: $data['dataLimitInGB'] ?? null,
      startDate: $data['startDate'] ?? null,
      endDate: $data['endDate'] ?? null,
      email: $data['email'] ?? null,
      referenceId: $data['referenceId'] ?? null,
      networkBrand: $data['networkBrand'] ?? null,
      emailBrand: $data['emailBrand'] ?? null,
      language: $data['language'] ?? null,
      startTime: $data['startTime'] ?? null,
      endTime: $data['endTime'] ?? null
    );
    $instance->_dirtyFields = [];
    foreach (
      [
        'destination',
        'dataLimitInGB',
        'startDate',
        'endDate',
        'email',
        'referenceId',
        'networkBrand',
        'emailBrand',
        'language',
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
    if (array_key_exists('destination', $this->_dirtyFields)) {
      $result['destination'] = $this->destination;
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
    if (array_key_exists('email', $this->_dirtyFields)) {
      $result['email'] = $this->email;
    }
    if (array_key_exists('referenceId', $this->_dirtyFields)) {
      $result['referenceId'] = $this->referenceId;
    }
    if (array_key_exists('networkBrand', $this->_dirtyFields)) {
      $result['networkBrand'] = $this->networkBrand;
    }
    if (array_key_exists('emailBrand', $this->_dirtyFields)) {
      $result['emailBrand'] = $this->emailBrand;
    }
    if (array_key_exists('language', $this->_dirtyFields)) {
      $result['language'] = $this->language;
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
