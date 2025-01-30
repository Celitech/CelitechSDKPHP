<?php

namespace Celitech\Services;

use Celitech\Utils\Serializer;
use Celitech\Models;

class Destinations extends BaseService
{
    /**
     * List Destinations
     */
    public function listDestinations(): Models\ListDestinationsOkResponse
    {
        $data = $this->sendRequest('get', '/destinations', ['scopes' => []]);

        return Serializer::deserialize($data, Models\ListDestinationsOkResponse::class);
    }
}
