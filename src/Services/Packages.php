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
class Packages extends BaseService
{
    /**
     * List Packages
     * @return Models\ListPackagesOkResponse
     */
    public function listPackages(
        string $destination = null,
        string $startDate = null,
        string $endDate = null,
        string $afterCursor = null,
        float $limit = null,
        int $startTime = null,
        int $endTime = null
    ): Models\ListPackagesOkResponse {
        $response = $this->sendRequest('get', '/packages', [
            'query' => [
                'destination' => $destination,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'afterCursor' => $afterCursor,
                'limit' => $limit,
                'startTime' => $startTime,
                'endTime' => $endTime,
            ],
            'scopes' => [],
        ]);
        $data = $response->getBody()->getContents();

        return Serializer::deserialize($data, Models\ListPackagesOkResponse::class);
    }
}
