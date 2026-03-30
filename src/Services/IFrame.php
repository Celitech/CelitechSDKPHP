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
class IFrame extends BaseService
{
    /**
     * Generate a new token to be used in the iFrame
     * @return Models\TokenOkResponse
     */
    public function token(): Models\TokenOkResponse
    {
        $response = $this->sendRequest('post', '/iframe/token', ['scopes' => []]);
        $data = $response->getBody()->getContents();

        return Serializer::deserialize($data, Models\TokenOkResponse::class);
    }
}
