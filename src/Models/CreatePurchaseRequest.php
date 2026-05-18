<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CreatePurchaseRequest implements \JsonSerializable
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
  public string $startDate;

  /**
   * End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date.
   */
  #[SerializedName('endDate')]
  public string $endDate;

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
  public ?CreatePurchaseRequestLanguage $language;

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
    string $destination,
    float $dataLimitInGb,
    string $startDate,
    string $endDate,
    ?string $email = null,
    ?string $referenceId = null,
    ?string $networkBrand = null,
    ?string $emailBrand = null,
    ?CreatePurchaseRequestLanguage $language = null,
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

    $this->_dirtyFields = [
      'destination' => true,
      'dataLimitInGB' => true,
      'startDate' => true,
      'endDate' => true
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
      destination: $data['destination'],
      dataLimitInGb: $data['dataLimitInGB'],
      startDate: $data['startDate'],
      endDate: $data['endDate'],
      email: $data['email'] ?? null,
      referenceId: $data['referenceId'] ?? null,
      networkBrand: $data['networkBrand'] ?? null,
      emailBrand: $data['emailBrand'] ?? null,
      language: isset($data['language']) &&
      (is_string($data['language']) || is_int($data['language']))
        ? CreatePurchaseRequestLanguage::tryFrom($data['language'])
        : null,
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
    $result['destination'] = $this->destination;
    $result['dataLimitInGB'] = $this->dataLimitInGb;
    $result['startDate'] = $this->startDate;
    $result['endDate'] = $this->endDate;
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
