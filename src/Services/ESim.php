<?php

namespace Celitech\Services;

use Celitech\Utils\Serializer;
use Celitech\Models;

class ESim extends BaseService
{
    /**
     * Get eSIM Status
     */
    public function getEsim(string $iccid): Models\GetEsimOkResponse
    {
        $data = $this->sendRequest('get', '/esim', [
            'query' => [
                'iccid' => $iccid,
            ],
            'scopes' => [],
        ]);

        return Serializer::deserialize($data, Models\GetEsimOkResponse::class);
    }

    /**
     * Get eSIM Device
     */
    public function getEsimDevice(string $iccid): Models\GetEsimDeviceOkResponse
    {
        $data = $this->sendRequest('get', "/esim/{$iccid}/device", ['scopes' => []]);

        return Serializer::deserialize($data, Models\GetEsimDeviceOkResponse::class);
    }

    /**
     * Get eSIM History
     */
    public function getEsimHistory(string $iccid): Models\GetEsimHistoryOkResponse
    {
        $data = $this->sendRequest('get', "/esim/{$iccid}/history", ['scopes' => []]);

        return Serializer::deserialize($data, Models\GetEsimHistoryOkResponse::class);
    }

    /**
     * Get eSIM MAC
     */
    public function getEsimMac(string $iccid): Models\GetEsimMacOkResponse
    {
        $data = $this->sendRequest('get', "/esim/{$iccid}/mac", ['scopes' => []]);

        return Serializer::deserialize($data, Models\GetEsimMacOkResponse::class);
    }
}
