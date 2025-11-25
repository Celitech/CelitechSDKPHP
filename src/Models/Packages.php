<?php

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
        float $minDays,
        float $maxDays,
        float $priceInCents
    ) {
        $this->id = $id;
        $this->destination = $destination;
        $this->destinationIso2 = $destinationIso2;
        $this->dataLimitInBytes = $dataLimitInBytes;
        $this->minDays = $minDays;
        $this->maxDays = $maxDays;
        $this->priceInCents = $priceInCents;
    }

    /**
     * Deserialize from array
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            destination: $data['destination'] ?? null,
            destinationIso2: $data['destinationISO2'] ?? null,
            dataLimitInBytes: $data['dataLimitInBytes'] ?? null,
            minDays: $data['minDays'] ?? null,
            maxDays: $data['maxDays'] ?? null,
            priceInCents: $data['priceInCents'] ?? null
        );
    }

    /**
     * Serialize to JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'destination' => $this->destination,
            'destinationISO2' => $this->destinationIso2,
            'dataLimitInBytes' => $this->dataLimitInBytes,
            'minDays' => $this->minDays,
            'maxDays' => $this->maxDays,
            'priceInCents' => $this->priceInCents,
        ];
    }
}
