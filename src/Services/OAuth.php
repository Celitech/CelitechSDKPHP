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
class OAuth extends BaseService
{
    /**
     * This endpoint was added by liblab
     * @return Models\GetAccessTokenOkResponse
     */
    public function getAccessToken(Models\GetAccessTokenRequest $input): Models\GetAccessTokenOkResponse
    {
        $response = $this->sendRequest('post', '/oauth2/token', ['form_params' => Serializer::serialize($input)]);
        $data = $response->getBody()->getContents();

        return Serializer::deserialize($data, Models\GetAccessTokenOkResponse::class);
    }
}
