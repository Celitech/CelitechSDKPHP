<?php

namespace Celitech\Services;

use Celitech\Utils\Serializer;
use Celitech\Models\ListDestinationsOkResponse;

class Destinations extends BaseService
{
    /**
     * List Destinations
     */
    public function listDestinations(): ListDestinationsOkResponse
    {
        $data = $this->sendRequest('get', '/destinations', []);

        return Serializer::deserialize($data, ListDestinationsOkResponse::class);
    }
}
