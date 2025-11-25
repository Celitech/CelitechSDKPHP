<?php

namespace Celitech\Services;

use Celitech\Utils\Serializer;
use Celitech\Models;

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
