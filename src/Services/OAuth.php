<?php

namespace Celitech\Services;

use Celitech\Utils\Serializer;
use Celitech\Models;

class OAuth extends BaseService
{
    /**
     * This endpoint was added by liblab
     */
    public function getAccessToken(Models\GetAccessTokenRequest $input): Models\GetAccessTokenOkResponse
    {
        $data = $this->sendRequest('post', '/oauth2/token', ['form_params' => Serializer::serialize($input)]);

        return Serializer::deserialize($data, Models\GetAccessTokenOkResponse::class);
    }
}
