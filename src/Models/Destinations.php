<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class Destinations
{
    /**
     * Name of the destination
     */
    #[SerializedName('name')]
    public ?string $name;

    /**
     * ISO3 representation of the destination
     */
    #[SerializedName('destination')]
    public ?string $destination;

    /**
     * ISO2 representation of the destination
     */
    #[SerializedName('destinationISO2')]
    public ?string $destinationIso2;

    /**
     * @var string[]|null
     * This array indicates the geographical area covered by a specific destination. If the destination represents a single country, the array will include that country. However, if the destination represents a broader regional scope, the array will be populated with the names of the countries belonging to that region.
     */
    #[SerializedName('supportedCountries')]
    public ?array $supportedCountries;

    public function __construct(
        ?string $name = null,
        ?string $destination = null,
        ?string $destinationIso2 = null,
        ?array $supportedCountries = []
    ) {
        $this->name = $name;
        $this->destination = $destination;
        $this->destinationIso2 = $destinationIso2;
        $this->supportedCountries = $supportedCountries;
    }
}
