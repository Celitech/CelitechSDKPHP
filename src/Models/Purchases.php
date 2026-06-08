<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class Purchases implements \JsonSerializable
{
  /**
   * ID of the purchase
   */
  #[SerializedName('id')]
  public string $id;

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
   * Duration of the package in days. Possible values are 1, 2, 7, 14, 30, or 90.
   */
  #[SerializedName('duration')]
  public ?float $duration;

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

  /**
   * Epoch value representing the date of creation of the purchase
   */
  #[SerializedName('createdAt')]
  public ?float $createdAt;

  #[SerializedName('package')]
  public Package $package;

  #[SerializedName('esim')]
  public PurchasesEsim $esim;

  /**
   * The `source` indicates whether the purchase was made from the API, dashboard, landing-page, promo-page or iframe. For purchases made before September 8, 2023, the value will be displayed as 'Not available'.
   */
  #[SerializedName('source')]
  public string $source;

  /**
   * The `purchaseType` indicates whether this is the initial purchase that creates the eSIM (First Purchase) or a subsequent top-up on an existing eSIM (Top-up Purchase).
   */
  #[SerializedName('purchaseType')]
  public string $purchaseType;

  /**
   * The `referenceId` that was provided by the partner during the purchase or top-up flow. This identifier can be used for analytics and debugging purposes.
   */
  #[SerializedName('referenceId')]
  public ?string $referenceId;

  /** @var array<string, true> Tracks which fields were explicitly set */
  private array $_dirtyFields = [];

  public function __construct(
    string $id,
    string $createdDate,
    Package $package,
    PurchasesEsim $esim,
    string $source,
    string $purchaseType,
    ?string $startDate = null,
    ?string $endDate = null,
    ?float $duration = null,
    ?float $startTime = null,
    ?float $endTime = null,
    ?float $createdAt = null,
    ?string $referenceId = null
  ) {
    $this->id = $id;
    $this->startDate = $startDate;
    $this->endDate = $endDate;
    $this->duration = $duration;
    $this->createdDate = $createdDate;
    $this->startTime = $startTime;
    $this->endTime = $endTime;
    $this->createdAt = $createdAt;
    $this->package = $package;
    $this->esim = $esim;
    $this->source = $source;
    $this->purchaseType = $purchaseType;
    $this->referenceId = $referenceId;

    $this->_dirtyFields = [
      'id' => true,
      'startDate' => true,
      'endDate' => true,
      'createdDate' => true,
      'package' => true,
      'esim' => true,
      'source' => true,
      'purchaseType' => true
    ];
    if ($duration !== null) {
      $this->_dirtyFields['duration'] = true;
    }
    if ($startTime !== null) {
      $this->_dirtyFields['startTime'] = true;
    }
    if ($endTime !== null) {
      $this->_dirtyFields['endTime'] = true;
    }
    if ($createdAt !== null) {
      $this->_dirtyFields['createdAt'] = true;
    }
    if ($referenceId !== null) {
      $this->_dirtyFields['referenceId'] = true;
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
      startDate: $data['startDate'],
      endDate: $data['endDate'],
      duration: $data['duration'] ?? null,
      createdDate: $data['createdDate'],
      startTime: $data['startTime'] ?? null,
      endTime: $data['endTime'] ?? null,
      createdAt: $data['createdAt'] ?? null,
      package: isset($data['package']) && is_array($data['package'])
        ? Package::fromArray($data['package'])
        : null,
      esim: isset($data['esim']) && is_array($data['esim'])
        ? PurchasesEsim::fromArray($data['esim'])
        : null,
      source: $data['source'],
      purchaseType: $data['purchaseType'],
      referenceId: $data['referenceId'] ?? null
    );
    $instance->_dirtyFields = [];
    foreach (
      [
        'id',
        'startDate',
        'endDate',
        'duration',
        'createdDate',
        'startTime',
        'endTime',
        'createdAt',
        'package',
        'esim',
        'source',
        'purchaseType',
        'referenceId'
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
    $result['id'] = $this->id;
    $result['startDate'] = $this->startDate;
    $result['endDate'] = $this->endDate;
    if (array_key_exists('duration', $this->_dirtyFields)) {
      $result['duration'] = $this->duration;
    }
    $result['createdDate'] = $this->createdDate;
    if (array_key_exists('startTime', $this->_dirtyFields)) {
      $result['startTime'] = $this->startTime;
    }
    if (array_key_exists('endTime', $this->_dirtyFields)) {
      $result['endTime'] = $this->endTime;
    }
    if (array_key_exists('createdAt', $this->_dirtyFields)) {
      $result['createdAt'] = $this->createdAt;
    }
    $result['package'] = $this->package;
    $result['esim'] = $this->esim;
    $result['source'] = $this->source;
    $result['purchaseType'] = $this->purchaseType;
    if (array_key_exists('referenceId', $this->_dirtyFields)) {
      $result['referenceId'] = $this->referenceId;
    }
    return $result;
  }

  public function validate(): void
  {
    $this->package->validate();
    $this->esim->validate();
  }
}
