<?php

namespace Celitech\Services;

use Celitech\Utils\Serializer;
use Celitech\Models;

class Iframe extends BaseService
{
    /**
     * Generate a new token to be used in the iframe
     */
    public function token(): Models\TokenOkResponse
    {
        $data = $this->sendRequest('get', '/iframe/token', ['scopes' => []]);

        return Serializer::deserialize($data, Models\TokenOkResponse::class);
    }
}
