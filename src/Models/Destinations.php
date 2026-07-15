<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class Destinations implements \JsonSerializable
{
  /**
   * Name of the destination
   */
  #[SerializedName('name')]
  public string $name;

  /**
   * ISO3 representation of the destination
   */
  #[SerializedName('destination')]
  public string $destination;

  /**
   * ISO2 representation of the destination
   */
  #[SerializedName('destinationISO2')]
  public string $destinationIso2;

  /**
   * @var string[]
   * This array indicates the geographical area covered by a specific destination. If the destination represents a single country, the array will include that country. However, if the destination represents a broader regional scope, the array will be populated with the names of the countries belonging to that region.
   */
  #[SerializedName('supportedCountries')]
  public array $supportedCountries;

  public function __construct(
    string $name,
    string $destination,
    string $destinationIso2,
    array $supportedCountries
  ) {
    $this->name = $name;
    $this->destination = $destination;
    $this->destinationIso2 = $destinationIso2;
    $this->supportedCountries = $supportedCountries;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    $instance = new self(
      name: $data['name'],
      destination: $data['destination'],
      destinationIso2: $data['destinationISO2'],
      supportedCountries: $data['supportedCountries']
    );

    return $instance;
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [];
    $result['name'] = $this->name;
    $result['destination'] = $this->destination;
    $result['destinationISO2'] = $this->destinationIso2;
    $result['supportedCountries'] = $this->supportedCountries;
    return $result;
  }

  public function toMultipart(): array
  {
    return [
      [
        'name' => 'name',
        'contents' => $this->name
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
        'name' => 'supportedCountries',
        'contents' => json_encode($this->supportedCountries)
      ]
    ];
  }

  public function validate(): void
  {
  }
}
