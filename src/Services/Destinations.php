<?php

namespace Celitech\Services;

use Celitech\Utils\Serializer;
use Celitech\Models;

class Destinations extends BaseService
{
    /**
     * List Destinations
     * @return Models\ListDestinationsOkResponse
     */
    public function listDestinations(): Models\ListDestinationsOkResponse
    {
        $response = $this->sendRequest('get', '/destinations', ['scopes' => []]);
        $data = $response->getBody()->getContents();

        return Serializer::deserialize($data, Models\ListDestinationsOkResponse::class);
    }
}
