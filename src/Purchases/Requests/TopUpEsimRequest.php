<?php

declare(strict_types=1);

namespace Celitech\Purchases\Requests;

use Celitech\Core\Json\JsonSerializableType;
use Celitech\Core\Json\ReflectiveSerialization;
use Celitech\Core\Json\JsonProperty;
use Celitech\Core\Types\Date;
use Celitech\Utils\Validator;
use DateTime;

/**
 * The reflective deserializer reconstructs this model with `new static($values)`, so a subclass has
 * to keep a constructor it can call with one associative array.
 *
 * @phpstan-consistent-constructor
 */
class TopUpEsimRequest extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * Optional. A unique value (for example a UUID) that makes this request safe to retry. If a request with the same `Idempotency-Key` and the same body succeeds, repeating it returns the original response instead of performing the action again, and the response includes the header `Idempotent-Replayed: true`. Keys are scoped to your credentials and remembered for 3 days. Only successful (2xx) responses are remembered, so a request that failed can be retried with the same key. Reusing a key with a different request returns `422`; sending it while the original request is still being processed returns `409`. If the idempotency store is temporarily unavailable, the request is not processed and returns `503`; retry it. Must be 1 to 255 printable ASCII characters, without spaces.
   */
  public ?string $idempotencyKey;

  /**
   * ID of the eSIM
   */
  #[JsonProperty('iccid', required: true)]
  public string $iccid;

  /**
   * Size of the package in GB. The available options are 0.5, 1, 2, 3, 5, 8, 20, 50GB. Use `-1` to top up with an unlimited (date-based) package — provide `startDate`/`endDate` spanning 3 to 30 days (`duration` is not supported for unlimited packages).
   */
  #[JsonProperty('dataLimitInGB', required: true)]
  public float $dataLimitInGb;

  /**
   * Start date of the package's validity in the format 'yyyy-MM-dd'. This date can be set to the current day or any day within the next 12 months.
   */
  #[JsonProperty('startDate'), Date(Date::TYPE_DATE)]
  public ?DateTime $startDate;

  /**
   * End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date.
   */
  #[JsonProperty('endDate'), Date(Date::TYPE_DATE)]
  public ?DateTime $endDate;

  /**
   * Duration of the package in days. Available values are 1, 2, 7, 14, 30, or 90. Either provide startDate/endDate or duration. Not supported for unlimited packages (`dataLimitInGB` = -1), which are date-based — provide startDate/endDate instead.
   */
  #[JsonProperty('duration')]
  public ?float $duration;

  /**
   * Email address where the purchase confirmation email will be sent (excluding QR Code & activation steps).
   */
  #[JsonProperty('email')]
  public ?string $email;

  /**
   * An identifier provided by the partner to link this purchase to their booking or transaction for analytics and debugging purposes. Letters, digits, `-`, `_`, `.`, `@` and `+` are allowed (so an email address works), up to 255 characters.
   */
  #[JsonProperty('referenceId')]
  public ?string $referenceId;

  /**
   * Customize the email subject brand. The `emailBrand` parameter cannot exceed 25 characters in length and must contain only letters, numbers, and spaces. This feature is available to platforms with Diamond tier only.
   */
  #[JsonProperty('emailBrand')]
  public ?string $emailBrand;

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
   *   iccid: string,
   *   dataLimitInGb: float,
   *   idempotencyKey?: ?string,
   *   startDate?: ?DateTime,
   *   endDate?: ?DateTime,
   *   duration?: ?float,
   *   email?: ?string,
   *   referenceId?: ?string,
   *   emailBrand?: ?string,
   *   startTime?: ?float,
   *   endTime?: ?float,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->idempotencyKey = $values['idempotencyKey'] ?? null;
    $this->iccid = $values['iccid'];
    $this->dataLimitInGb = $values['dataLimitInGb'];
    $this->startDate = $values['startDate'] ?? null;
    $this->endDate = $values['endDate'] ?? null;
    $this->duration = $values['duration'] ?? null;
    $this->email = $values['email'] ?? null;
    $this->referenceId = $values['referenceId'] ?? null;
    $this->emailBrand = $values['emailBrand'] ?? null;
    $this->startTime = $values['startTime'] ?? null;
    $this->endTime = $values['endTime'] ?? null;

    $this->_dirtyFields = [
      'iccid' => true,
      'dataLimitInGB' => true
    ];
    if (array_key_exists('startDate', $values)) {
      $this->_dirtyFields['startDate'] = true;
    }
    if (array_key_exists('endDate', $values)) {
      $this->_dirtyFields['endDate'] = true;
    }
    if (array_key_exists('duration', $values)) {
      $this->_dirtyFields['duration'] = true;
    }
    if (array_key_exists('email', $values)) {
      $this->_dirtyFields['email'] = true;
    }
    if (array_key_exists('referenceId', $values)) {
      $this->_dirtyFields['referenceId'] = true;
    }
    if (array_key_exists('emailBrand', $values)) {
      $this->_dirtyFields['emailBrand'] = true;
    }
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
      'name' => 'iccid',
      'contents' => $this->iccid
    ];

    $parts[] = [
      'name' => 'dataLimitInGB',
      'contents' => (string) $this->dataLimitInGb
    ];

    if ($this->startDate !== null) {
      $parts[] = [
        'name' => 'startDate',
        'contents' => $this->startDate
      ];
    }

    if ($this->endDate !== null) {
      $parts[] = [
        'name' => 'endDate',
        'contents' => $this->endDate
      ];
    }

    if ($this->duration !== null) {
      $parts[] = [
        'name' => 'duration',
        'contents' => (string) $this->duration
      ];
    }

    if ($this->email !== null) {
      $parts[] = [
        'name' => 'email',
        'contents' => $this->email
      ];
    }

    if ($this->referenceId !== null) {
      $parts[] = [
        'name' => 'referenceId',
        'contents' => $this->referenceId
      ];
    }

    if ($this->emailBrand !== null) {
      $parts[] = [
        'name' => 'emailBrand',
        'contents' => $this->emailBrand
      ];
    }

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
    Validator::validateString($this->iccid, 'iccid', minLength: 18, maxLength: 22);
    Validator::validateString(
      $this->referenceId,
      'referenceId',
      maxLength: 255,
      pattern: '^[a-zA-Z0-9\\-_.@+]+$'
    );
  }
}
