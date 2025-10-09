<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class ListDestinationsOkResponse
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
}
