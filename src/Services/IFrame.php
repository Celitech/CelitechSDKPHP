<?php

namespace Celitech\Services;

use Celitech\Utils\Serializer;
use Celitech\Models;

class IFrame extends BaseService
{
    /**
     * Generate a new token to be used in the iFrame
     */
    public function token(): Models\TokenOkResponse
    {
        $data = $this->sendRequest('post', '/iframe/token', ['scopes' => []]);

        return Serializer::deserialize($data, Models\TokenOkResponse::class);
    }
}
