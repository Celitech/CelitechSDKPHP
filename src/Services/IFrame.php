<?php

namespace Celitech\Services;

use Celitech\Utils\Serializer;
use Celitech\Models;

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
