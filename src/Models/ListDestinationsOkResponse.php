<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class ListDestinationsOkResponse implements \JsonSerializable
{
    /**
     * @var Destinations[]
     */
    #[SerializedName('destinations')]
    public array $destinations;

    public function __construct(array $destinations)
    {
        $this->destinations = $destinations;
    }

    /**
     * Deserialize from array
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(destinations: $data['destinations'] ?? null);
    }

    /**
     * Serialize to JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'destinations' => $this->destinations,
        ];
    }
}
