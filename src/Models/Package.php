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
   * Size of the package in Bytes. A value of `-1` indicates an unlimited package.
   */
  #[SerializedName('dataLimitInBytes')]
  public float $dataLimitInBytes;

  /**
   * Size of the package in GB. A value of `-1` indicates an unlimited (date-based) package.
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
    $instance = new self(
      id: $data['id'],
      dataLimitInBytes: $data['dataLimitInBytes'],
      dataLimitInGb: $data['dataLimitInGB'],
      destination: $data['destination'],
      destinationIso2: $data['destinationISO2'],
      destinationName: $data['destinationName'],
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
    $result['dataLimitInBytes'] = $this->dataLimitInBytes;
    $result['dataLimitInGB'] = $this->dataLimitInGb;
    $result['destination'] = $this->destination;
    $result['destinationISO2'] = $this->destinationIso2;
    $result['destinationName'] = $this->destinationName;
    $result['priceInCents'] = $this->priceInCents;
    return $result;
  }

  public function toMultipart(): array
  {
    return [
      [
        'name' => 'id',
        'contents' => $this->id
      ],

      [
        'name' => 'dataLimitInBytes',
        'contents' => (string) $this->dataLimitInBytes
      ],

      [
        'name' => 'dataLimitInGb',
        'contents' => (string) $this->dataLimitInGb
      ],

      [
        'name' => 'destination',
        'contents' => $this->destination
      ],

      [
        'name' => 'destinationIso2',
        'contents' => $this->destinationIso2
      ],

      [
        'name' => 'destinationName',
        'contents' => $this->destinationName
      ],

      [
        'name' => 'priceInCents',
        'contents' => (string) $this->priceInCents
      ]
    ];
  }

  public function validate(): void
  {
  }
}
