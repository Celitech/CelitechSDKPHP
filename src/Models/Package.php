<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class Package
{
    /**
     * ID of the package
     */
    #[SerializedName('id')]
    public ?string $id;

    /**
     * Size of the package in Bytes
     */
    #[SerializedName('dataLimitInBytes')]
    public ?float $dataLimitInBytes;

    /**
     * ISO3 representation of the package's destination.
     */
    #[SerializedName('destination')]
    public ?string $destination;

    /**
     * ISO2 representation of the package's destination.
     */
    #[SerializedName('destinationISO2')]
    public ?string $destinationIso2;

    /**
     * Name of the package's destination
     */
    #[SerializedName('destinationName')]
    public ?string $destinationName;

    /**
     * Price of the package in cents
     */
    #[SerializedName('priceInCents')]
    public ?float $priceInCents;

    public function __construct(
        ?string $id = null,
        ?float $dataLimitInBytes = null,
        ?string $destination = null,
        ?string $destinationIso2 = null,
        ?string $destinationName = null,
        ?float $priceInCents = null
    ) {
        $this->id = $id;
        $this->dataLimitInBytes = $dataLimitInBytes;
        $this->destination = $destination;
        $this->destinationIso2 = $destinationIso2;
        $this->destinationName = $destinationName;
        $this->priceInCents = $priceInCents;
    }
}
