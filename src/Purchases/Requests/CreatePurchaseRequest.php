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
class CreatePurchaseRequest extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * Optional. A unique value (for example a UUID) that makes this request safe to retry. If a request with the same `Idempotency-Key` and the same body succeeds, repeating it returns the original response instead of performing the action again, and the response includes the header `Idempotent-Replayed: true`. Keys are scoped to your credentials and remembered for 3 days. Only successful (2xx) responses are remembered, so a request that failed can be retried with the same key. Reusing a key with a different request returns `422`; sending it while the original request is still being processed returns `409`. If the idempotency store is temporarily unavailable, the request is not processed and returns `503`; retry it. Must be 1 to 255 printable ASCII characters, without spaces.
   */
  public ?string $idempotencyKey;

  /**
   * ISO representation of the package's destination. Supports both ISO2 (e.g., 'FR') and ISO3 (e.g., 'FRA') country codes.
   */
  #[JsonProperty('destination', required: true)]
  public string $destination;

  /**
   * Size of the package in GB. The available options are 0.5, 1, 2, 3, 5, 8, 20, 50GB. Use `-1` to purchase an unlimited (date-based) package — provide `startDate`/`endDate` spanning 3 to 30 days.
   */
  #[JsonProperty('dataLimitInGB', required: true)]
  public float $dataLimitInGb;

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
   * Email address where the purchase confirmation email will be sent (including QR Code & activation steps)
   */
  #[JsonProperty('email')]
  public ?string $email;

  /**
   * An identifier provided by the partner to link this purchase to their booking or transaction for analytics and debugging purposes. Letters, digits, `-`, `_`, `.`, `@` and `+` are allowed (so an email address works), up to 255 characters.
   */
  #[JsonProperty('referenceId')]
  public ?string $referenceId;

  /**
   * Customize the network brand of the issued eSIM. The `networkBrand` parameter cannot exceed 15 characters in length and must contain only letters, numbers, dots (.), ampersands (&), and spaces. This feature is available to platforms with Diamond tier only.
   */
  #[JsonProperty('networkBrand')]
  public ?string $networkBrand;

  /**
   * Customize the email subject brand. The `emailBrand` parameter cannot exceed 25 characters in length and must contain only letters, numbers, and spaces. This feature is available to platforms with Diamond tier only.
   */
  #[JsonProperty('emailBrand')]
  public ?string $emailBrand;

  /**
   * @var ?value-of<\Celitech\Purchases\Types\CreatePurchaseRequestLanguage>
   * Language of the confirmation email sent to the customer.
   */
  #[JsonProperty('language')]
  public ?string $language;

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
   *   destination: string,
   *   dataLimitInGb: float,
   *   startDate: DateTime,
   *   endDate: DateTime,
   *   idempotencyKey?: ?string,
   *   email?: ?string,
   *   referenceId?: ?string,
   *   networkBrand?: ?string,
   *   emailBrand?: ?string,
   *   language?: ?value-of<\Celitech\Purchases\Types\CreatePurchaseRequestLanguage>,
   *   startTime?: ?float,
   *   endTime?: ?float,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->idempotencyKey = $values['idempotencyKey'] ?? null;
    $this->destination = $values['destination'];
    $this->dataLimitInGb = $values['dataLimitInGb'];
    $this->startDate = $values['startDate'];
    $this->endDate = $values['endDate'];
    $this->email = $values['email'] ?? null;
    $this->referenceId = $values['referenceId'] ?? null;
    $this->networkBrand = $values['networkBrand'] ?? null;
    $this->emailBrand = $values['emailBrand'] ?? null;
    $this->language = $values['language'] ?? null;
    $this->startTime = $values['startTime'] ?? null;
    $this->endTime = $values['endTime'] ?? null;

    $this->_dirtyFields = [
      'destination' => true,
      'dataLimitInGB' => true,
      'startDate' => true,
      'endDate' => true
    ];
    if (array_key_exists('email', $values)) {
      $this->_dirtyFields['email'] = true;
    }
    if (array_key_exists('referenceId', $values)) {
      $this->_dirtyFields['referenceId'] = true;
    }
    if (array_key_exists('networkBrand', $values)) {
      $this->_dirtyFields['networkBrand'] = true;
    }
    if (array_key_exists('emailBrand', $values)) {
      $this->_dirtyFields['emailBrand'] = true;
    }
    if (array_key_exists('language', $values)) {
      $this->_dirtyFields['language'] = true;
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
      'name' => 'destination',
      'contents' => $this->destination
    ];

    $parts[] = [
      'name' => 'dataLimitInGB',
      'contents' => (string) $this->dataLimitInGb
    ];

    $parts[] = [
      'name' => 'startDate',
      'contents' => $this->startDate
    ];

    $parts[] = [
      'name' => 'endDate',
      'contents' => $this->endDate
    ];

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

    if ($this->networkBrand !== null) {
      $parts[] = [
        'name' => 'networkBrand',
        'contents' => $this->networkBrand
      ];
    }

    if ($this->emailBrand !== null) {
      $parts[] = [
        'name' => 'emailBrand',
        'contents' => $this->emailBrand
      ];
    }

    if ($this->language !== null) {
      $parts[] = [
        'name' => 'language',
        'contents' => $this->language
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
    Validator::validateString(
      $this->referenceId,
      'referenceId',
      maxLength: 255,
      pattern: '^[a-zA-Z0-9\\-_.@+]+$'
    );
  }
}
