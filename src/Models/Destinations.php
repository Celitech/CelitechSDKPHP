<?php

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

    public function __construct(string $name, string $destination, string $destinationIso2, array $supportedCountries)
    {
        $this->name = $name;
        $this->destination = $destination;
        $this->destinationIso2 = $destinationIso2;
        $this->supportedCountries = $supportedCountries;
    }

    /**
     * Deserialize from array
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            destination: $data['destination'] ?? null,
            destinationIso2: $data['destinationISO2'] ?? null,
            supportedCountries: $data['supportedCountries'] ?? null
        );
    }

    /**
     * Serialize to JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'destination' => $this->destination,
            'destinationISO2' => $this->destinationIso2,
            'supportedCountries' => $this->supportedCountries,
        ];
    }
}
