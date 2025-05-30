<?php

namespace Celitech\Services;

use Celitech\Utils\Serializer;
use Celitech\Models;

class Purchases extends BaseService
{
    /**
     * This endpoint is used to purchase a new eSIM by providing the package details.
     */
    public function createPurchaseV2(Models\CreatePurchaseV2Request $input): array
    {
        $data = $this->sendRequest('post', '/purchases/v2', ['json' => Serializer::serialize($input), 'scopes' => []]);

        return json_decode($data, true);
    }

    /**
     * This endpoint can be used to list all the successful purchases made between a given interval.
     */
    public function listPurchases(
        string $iccid = null,
        string $afterDate = null,
        string $beforeDate = null,
        string $referenceId = null,
        string $afterCursor = null,
        float $limit = null,
        float $after = null,
        float $before = null
    ): Models\ListPurchasesOkResponse {
        $data = $this->sendRequest('get', '/purchases', [
            'query' => [
                'iccid' => $iccid,
                'afterDate' => $afterDate,
                'beforeDate' => $beforeDate,
                'referenceId' => $referenceId,
                'afterCursor' => $afterCursor,
                'limit' => $limit,
                'after' => $after,
                'before' => $before,
            ],
            'scopes' => [],
        ]);

        return Serializer::deserialize($data, Models\ListPurchasesOkResponse::class);
    }

    /**
     * This endpoint is used to purchase a new eSIM by providing the package details.
     */
    public function createPurchase(Models\CreatePurchaseRequest $input): Models\CreatePurchaseOkResponse
    {
        $data = $this->sendRequest('post', '/purchases', ['json' => Serializer::serialize($input), 'scopes' => []]);

        return Serializer::deserialize($data, Models\CreatePurchaseOkResponse::class);
    }

    /**
     * This endpoint is used to top-up an eSIM with the previously associated destination by providing an existing ICCID and the package details. The top-up is only feasible for eSIMs in "ENABLED" or "INSTALLED" state. You can check this state using the Get eSIM Status endpoint.
     */
    public function topUpEsim(Models\TopUpEsimRequest $input): Models\TopUpEsimOkResponse
    {
        $data = $this->sendRequest('post', '/purchases/topup', [
            'json' => Serializer::serialize($input),
            'scopes' => [],
        ]);

        return Serializer::deserialize($data, Models\TopUpEsimOkResponse::class);
    }

    /**
     * This endpoint allows you to modify the dates of an existing package with a future activation start time. Editing can only be performed for packages that have not been activated, and it cannot change the package size. The modification must not change the package duration category to ensure pricing consistency.
     */
    public function editPurchase(Models\EditPurchaseRequest $input): Models\EditPurchaseOkResponse
    {
        $data = $this->sendRequest('post', '/purchases/edit', [
            'json' => Serializer::serialize($input),
            'scopes' => [],
        ]);

        return Serializer::deserialize($data, Models\EditPurchaseOkResponse::class);
    }

    /**
     * This endpoint can be called for consumption notifications (e.g. every 1 hour or when the user clicks a button). It returns the data balance (consumption) of purchased packages.
     */
    public function getPurchaseConsumption(string $purchaseId): Models\GetPurchaseConsumptionOkResponse
    {
        $data = $this->sendRequest('get', "/purchases/{$purchaseId}/consumption", ['scopes' => []]);

        return Serializer::deserialize($data, Models\GetPurchaseConsumptionOkResponse::class);
    }
}
