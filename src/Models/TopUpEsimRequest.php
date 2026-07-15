<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Celitech\Utils\Validator;

class TopUpEsimRequest implements \JsonSerializable
{
  /**
   * ID of the eSIM
   */
  #[SerializedName('iccid')]
  public string $iccid;

  /**
   * Size of the package in GB. The available options are 0.5, 1, 2, 3, 5, 8, 20, 50GB. Use `-1` to top up with an unlimited (date-based) package — provide `startDate`/`endDate` spanning 3 to 30 days (`duration` is not supported for unlimited packages).
   */
  #[SerializedName('dataLimitInGB')]
  public float $dataLimitInGb;

  /**
   * Start date of the package's validity in the format 'yyyy-MM-dd'. This date can be set to the current day or any day within the next 12 months.
   */
  #[SerializedName('startDate')]
  public ?string $startDate;

  /**
   * End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date.
   */
  #[SerializedName('endDate')]
  public ?string $endDate;

  /**
   * Duration of the package in days. Available values are 1, 2, 7, 14, 30, or 90. Either provide startDate/endDate or duration. Not supported for unlimited packages (`dataLimitInGB` = -1), which are date-based — provide startDate/endDate instead.
   */
  #[SerializedName('duration')]
  public ?float $duration;

  /**
   * Email address where the purchase confirmation email will be sent (excluding QR Code & activation steps).
   */
  #[SerializedName('email')]
  public ?string $email;

  /**
   * An identifier provided by the partner to link this purchase to their booking or transaction for analytics and debugging purposes.
   */
  #[SerializedName('referenceId')]
  public ?string $referenceId;

  /**
   * Customize the email subject brand. The `emailBrand` parameter cannot exceed 25 characters in length and must contain only letters, numbers, and spaces. This feature is available to platforms with Diamond tier only.
   */
  #[SerializedName('emailBrand')]
  public ?string $emailBrand;

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
    string $iccid,
    float $dataLimitInGb,
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

    $this->_dirtyFields = [
      'iccid' => true,
      'dataLimitInGB' => true
    ];
    if ($startDate !== null) {
      $this->_dirtyFields['startDate'] = true;
    }
    if ($endDate !== null) {
      $this->_dirtyFields['endDate'] = true;
    }
    if ($duration !== null) {
      $this->_dirtyFields['duration'] = true;
    }
    if ($email !== null) {
      $this->_dirtyFields['email'] = true;
    }
    if ($referenceId !== null) {
      $this->_dirtyFields['referenceId'] = true;
    }
    if ($emailBrand !== null) {
      $this->_dirtyFields['emailBrand'] = true;
    }
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
      iccid: $data['iccid'],
      dataLimitInGb: $data['dataLimitInGB'],
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
    $result['iccid'] = $this->iccid;
    $result['dataLimitInGB'] = $this->dataLimitInGb;
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

  public function toMultipart(): array
  {
    return [
      [
        'name' => 'iccid',
        'contents' => $this->iccid
      ],

      [
        'name' => 'dataLimitInGb',
        'contents' => (string) $this->dataLimitInGb
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
        'name' => 'duration',
        'contents' => (string) $this->duration
      ],

      [
        'name' => 'email',
        'contents' => $this->email
      ],

      [
        'name' => 'referenceId',
        'contents' => $this->referenceId
      ],

      [
        'name' => 'emailBrand',
        'contents' => $this->emailBrand
      ],

      [
        'name' => 'startTime',
        'contents' => (string) $this->startTime
      ],

      [
        'name' => 'endTime',
        'contents' => (string) $this->endTime
      ]
    ];
  }

  public function validate(): void
  {
    Validator::validateString($this->iccid, 'iccid', minLength: 18, maxLength: 22);
  }
}
