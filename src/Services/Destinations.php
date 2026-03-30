<?php

namespace Celitech\Services;

use Celitech\Utils\Serializer;
use Celitech\Models;

/**
 * Service class containing API endpoint methods.
 *
 * This class extends the base service to provide typed methods for specific API operations.
 * Each method corresponds to an API endpoint and handles request serialization,
 * execution, and response deserialization.
 */
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
