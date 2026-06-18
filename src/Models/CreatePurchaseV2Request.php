<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Celitech\Utils\Validator;

class CreatePurchaseV2Request implements \JsonSerializable
{
  /**
   * ISO representation of the package's destination. Supports both ISO2 (e.g., 'FR') and ISO3 (e.g., 'FRA') country codes.
   */
  #[SerializedName('destination')]
  public string $destination;

  /**
   * Size of the package in GB. The available options are 0.5, 1, 2, 3, 5, 8, 20, 50GB
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
   * Duration of the package in days. Available values are 1, 2, 7, 14, 30, or 90. Either provide startDate/endDate or duration.
   */
  #[SerializedName('duration')]
  public ?float $duration;

  /**
   * Number of eSIMs to purchase.
   */
  #[SerializedName('quantity')]
  public float $quantity;

  /**
   * Email address where the purchase confirmation email will be sent (including QR Code & activation steps)
   */
  #[SerializedName('email')]
  public ?string $email;

  /**
   * An identifier provided by the partner to link this purchase to their booking or transaction for analytics and debugging purposes.
   */
  #[SerializedName('referenceId')]
  public ?string $referenceId;

  /**
   * Customize the network brand of the issued eSIM. The `networkBrand` parameter cannot exceed 15 characters in length and must contain only letters, numbers, dots (.), ampersands (&), and spaces. This feature is available to platforms with Diamond tier only.
   */
  #[SerializedName('networkBrand')]
  public ?string $networkBrand;

  /**
   * Customize the email subject brand. The `emailBrand` parameter cannot exceed 25 characters in length and must contain only letters, numbers, and spaces. This feature is available to platforms with Diamond tier only.
   */
  #[SerializedName('emailBrand')]
  public ?string $emailBrand;

  /**
   * Language of the confirmation email sent to the customer.
   */
  #[SerializedName('language')]
  public ?CreatePurchaseV2RequestLanguage $language;

  /** @var array<string, true> Tracks which fields were explicitly set */
  private array $_dirtyFields = [];

  public function __construct(
    string $destination,
    float $dataLimitInGb,
    float $quantity,
    ?string $startDate = null,
    ?string $endDate = null,
    ?float $duration = null,
    ?string $email = null,
    ?string $referenceId = null,
    ?string $networkBrand = null,
    ?string $emailBrand = null,
    ?CreatePurchaseV2RequestLanguage $language = null
  ) {
    $this->destination = $destination;
    $this->dataLimitInGb = $dataLimitInGb;
    $this->startDate = $startDate;
    $this->endDate = $endDate;
    $this->duration = $duration;
    $this->quantity = $quantity;
    $this->email = $email;
    $this->referenceId = $referenceId;
    $this->networkBrand = $networkBrand;
    $this->emailBrand = $emailBrand;
    $this->language = $language;

    $this->_dirtyFields = [
      'destination' => true,
      'dataLimitInGB' => true,
      'quantity' => true
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
    if ($networkBrand !== null) {
      $this->_dirtyFields['networkBrand'] = true;
    }
    if ($emailBrand !== null) {
      $this->_dirtyFields['emailBrand'] = true;
    }
    if ($language !== null) {
      $this->_dirtyFields['language'] = true;
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
      destination: $data['destination'],
      dataLimitInGb: $data['dataLimitInGB'],
      startDate: $data['startDate'] ?? null,
      endDate: $data['endDate'] ?? null,
      duration: $data['duration'] ?? null,
      quantity: $data['quantity'],
      email: $data['email'] ?? null,
      referenceId: $data['referenceId'] ?? null,
      networkBrand: $data['networkBrand'] ?? null,
      emailBrand: $data['emailBrand'] ?? null,
      language: isset($data['language']) &&
      (is_string($data['language']) || is_int($data['language']))
        ? CreatePurchaseV2RequestLanguage::tryFrom($data['language'])
        : null
    );
    $instance->_dirtyFields = [];
    foreach (
      [
        'destination',
        'dataLimitInGB',
        'startDate',
        'endDate',
        'duration',
        'quantity',
        'email',
        'referenceId',
        'networkBrand',
        'emailBrand',
        'language'
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
    $result['destination'] = $this->destination;
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
    $result['quantity'] = $this->quantity;
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
    return $result;
  }

  public function toMultipart(): array
  {
    return [
      [
        'name' => 'destination',
        'contents' => $this->destination
      ],

      [
        'name' => 'dataLimitInGb',
        'contents' => $this->dataLimitInGb
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
        'contents' => $this->duration
      ],

      [
        'name' => 'quantity',
        'contents' => $this->quantity
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
        'name' => 'networkBrand',
        'contents' => $this->networkBrand
      ],

      [
        'name' => 'emailBrand',
        'contents' => $this->emailBrand
      ],

      [
        'name' => 'language',
        'contents' => $this->language
      ]
    ];
  }

  public function validate(): void
  {
    Validator::validateNumber($this->quantity, 'quantity', min: 1, max: 5);
  }
}
