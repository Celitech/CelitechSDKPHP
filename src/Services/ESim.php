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
class ESim extends BaseService
{
    /**
     * Get eSIM
     * @return Models\GetEsimOkResponse
     */
    public function getEsim(string $iccid): Models\GetEsimOkResponse
    {
        $response = $this->sendRequest('get', '/esim', [
            'query' => [
                'iccid' => $iccid,
            ],
            'scopes' => [],
        ]);
        $data = $response->getBody()->getContents();

        return Serializer::deserialize($data, Models\GetEsimOkResponse::class);
    }

    /**
     * Get eSIM Device
     * @return Models\GetEsimDeviceOkResponse
     */
    public function getEsimDevice(string $iccid): Models\GetEsimDeviceOkResponse
    {
        $response = $this->sendRequest('get', "/esim/{$iccid}/device", ['scopes' => []]);
        $data = $response->getBody()->getContents();

        return Serializer::deserialize($data, Models\GetEsimDeviceOkResponse::class);
    }

    /**
     * Get eSIM History
     * @return Models\GetEsimHistoryOkResponse
     */
    public function getEsimHistory(string $iccid): Models\GetEsimHistoryOkResponse
    {
        $response = $this->sendRequest('get', "/esim/{$iccid}/history", ['scopes' => []]);
        $data = $response->getBody()->getContents();

        return Serializer::deserialize($data, Models\GetEsimHistoryOkResponse::class);
    }
}
