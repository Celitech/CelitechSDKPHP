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
class CreatePurchaseV2Request extends JsonSerializableType
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
   * Size of the package in GB. The available options are 0.5, 1, 2, 3, 5, 8, 20, 50GB. Use `-1` to purchase an unlimited (date-based) package — provide `startDate`/`endDate` spanning 3 to 30 days (`duration` is not supported for unlimited packages).
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
   * Number of eSIMs to purchase.
   */
  #[JsonProperty('quantity', required: true)]
  public float $quantity;

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
   * @var ?value-of<\Celitech\Purchases\Types\CreatePurchaseV2RequestLanguage>
   * Language of the confirmation email sent to the customer.
   */
  #[JsonProperty('language')]
  public ?string $language;

  /**
   * @param array{
   *   destination: string,
   *   dataLimitInGb: float,
   *   quantity: float,
   *   idempotencyKey?: ?string,
   *   startDate?: ?DateTime,
   *   endDate?: ?DateTime,
   *   duration?: ?float,
   *   email?: ?string,
   *   referenceId?: ?string,
   *   networkBrand?: ?string,
   *   emailBrand?: ?string,
   *   language?: ?value-of<\Celitech\Purchases\Types\CreatePurchaseV2RequestLanguage>,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->idempotencyKey = $values['idempotencyKey'] ?? null;
    $this->destination = $values['destination'];
    $this->dataLimitInGb = $values['dataLimitInGb'];
    $this->startDate = $values['startDate'] ?? null;
    $this->endDate = $values['endDate'] ?? null;
    $this->duration = $values['duration'] ?? null;
    $this->quantity = $values['quantity'];
    $this->email = $values['email'] ?? null;
    $this->referenceId = $values['referenceId'] ?? null;
    $this->networkBrand = $values['networkBrand'] ?? null;
    $this->emailBrand = $values['emailBrand'] ?? null;
    $this->language = $values['language'] ?? null;

    $this->_dirtyFields = [
      'destination' => true,
      'dataLimitInGB' => true,
      'quantity' => true
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
    if (array_key_exists('networkBrand', $values)) {
      $this->_dirtyFields['networkBrand'] = true;
    }
    if (array_key_exists('emailBrand', $values)) {
      $this->_dirtyFields['emailBrand'] = true;
    }
    if (array_key_exists('language', $values)) {
      $this->_dirtyFields['language'] = true;
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

    $parts[] = [
      'name' => 'quantity',
      'contents' => (string) $this->quantity
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

    return $parts;
  }

  public function validate(): void
  {
    Validator::validateNumber($this->quantity, 'quantity', min: 1, max: 5);
    Validator::validateString(
      $this->referenceId,
      'referenceId',
      maxLength: 255,
      pattern: '^[a-zA-Z0-9\\-_.@+]+$'
    );
  }
}
