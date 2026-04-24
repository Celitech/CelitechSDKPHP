<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class Package implements \JsonSerializable
{
  /**
   * ID of the package
   */
  #[SerializedName('id')]
  public string $id;

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
   * Name of the package's destination
   */
  #[SerializedName('destinationName')]
  public string $destinationName;

  /**
   * Price of the package in cents
   */
  #[SerializedName('priceInCents')]
  public float $priceInCents;

  public function __construct(
    string $id,
    float $dataLimitInBytes,
    float $dataLimitInGb,
    string $destination,
    string $destinationIso2,
    string $destinationName,
    float $priceInCents
  ) {
    $this->id = $id;
    $this->dataLimitInBytes = $dataLimitInBytes;
    $this->dataLimitInGb = $dataLimitInGb;
    $this->destination = $destination;
    $this->destinationIso2 = $destinationIso2;
    $this->destinationName = $destinationName;
    $this->priceInCents = $priceInCents;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    return new self(
      id: $data['id'] ?? null,
      dataLimitInBytes: $data['dataLimitInBytes'] ?? null,
      dataLimitInGb: $data['dataLimitInGB'] ?? null,
      destination: $data['destination'] ?? null,
      destinationIso2: $data['destinationISO2'] ?? null,
      destinationName: $data['destinationName'] ?? null,
      priceInCents: $data['priceInCents'] ?? null
    );
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [
      'id' => $this->id,
      'dataLimitInBytes' => $this->dataLimitInBytes,
      'dataLimitInGB' => $this->dataLimitInGb,
      'destination' => $this->destination,
      'destinationISO2' => $this->destinationIso2,
      'destinationName' => $this->destinationName,
      'priceInCents' => $this->priceInCents
    ];

    return $result;
  }
}
