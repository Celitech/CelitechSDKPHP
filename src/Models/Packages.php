<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class Packages implements \JsonSerializable
{
  /**
   * ID of the package
   */
  #[SerializedName('id')]
  public string $id;

  /**
   * ISO3 representation of the package's destination.
   */
  #[SerializedName('destination')]
  public string $destination;

  /**
   * ISO2 representation of the package's destination.
   */
  #[SerializedName('destinationISO2')]
  public string $destinationIso2;

  /**
   * Size of the package in Bytes
   */
  #[SerializedName('dataLimitInBytes')]
  public float $dataLimitInBytes;

  /**
   * Size of the package in GB
   */
  #[SerializedName('dataLimitInGB')]
  public float $dataLimitInGb;

  /**
   * Min number of days for the package
   */
  #[SerializedName('minDays')]
  public float $minDays;

  /**
   * Max number of days for the package
   */
  #[SerializedName('maxDays')]
  public float $maxDays;

  /**
   * Price of the package in cents
   */
  #[SerializedName('priceInCents')]
  public float $priceInCents;

  public function __construct(
    string $id,
    string $destination,
    string $destinationIso2,
    float $dataLimitInBytes,
    float $dataLimitInGb,
    float $minDays,
    float $maxDays,
    float $priceInCents
  ) {
    $this->id = $id;
    $this->destination = $destination;
    $this->destinationIso2 = $destinationIso2;
    $this->dataLimitInBytes = $dataLimitInBytes;
    $this->dataLimitInGb = $dataLimitInGb;
    $this->minDays = $minDays;
    $this->maxDays = $maxDays;
    $this->priceInCents = $priceInCents;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    $instance = new self(
      id: $data['id'],
      destination: $data['destination'],
      destinationIso2: $data['destinationISO2'],
      dataLimitInBytes: $data['dataLimitInBytes'],
      dataLimitInGb: $data['dataLimitInGB'],
      minDays: $data['minDays'],
      maxDays: $data['maxDays'],
      priceInCents: $data['priceInCents']
    );

    return $instance;
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [];
    $result['id'] = $this->id;
    $result['destination'] = $this->destination;
    $result['destinationISO2'] = $this->destinationIso2;
    $result['dataLimitInBytes'] = $this->dataLimitInBytes;
    $result['dataLimitInGB'] = $this->dataLimitInGb;
    $result['minDays'] = $this->minDays;
    $result['maxDays'] = $this->maxDays;
    $result['priceInCents'] = $this->priceInCents;
    return $result;
  }

  public function validate(): void
  {
  }
}
