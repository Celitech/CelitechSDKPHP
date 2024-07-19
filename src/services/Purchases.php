<?php

namespace Celitech\Services;

use Celitech\Utils\Serializer;
use Celitech\Models\CreatePurchaseOkResponse;
use Celitech\Models\CreatePurchaseRequest;
use Celitech\Models\EditPurchaseOkResponse;
use Celitech\Models\EditPurchaseRequest;
use Celitech\Models\GetPurchaseConsumptionOkResponse;
use Celitech\Models\ListDestinationsOkResponse;
use Celitech\Models\ListPackagesOkResponse;
use Celitech\Models\ListPurchasesOkResponse;
use Celitech\Models\TopUpEsimOkResponse;
use Celitech\Models\TopUpEsimRequest;

class Purchases extends BaseService
{
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
    ): ListPurchasesOkResponse {
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
        ]);

        return Serializer::deserialize($data, ListPurchasesOkResponse::class);
    }

    /**
     * This endpoint is used to purchase a new eSIM by providing the package details.
     */
    public function createPurchase(CreatePurchaseRequest $input): CreatePurchaseOkResponse
    {
        $data = $this->sendRequest('post', '/purchases', ['json' => Serializer::serialize($input)]);

        return Serializer::deserialize($data, CreatePurchaseOkResponse::class);
    }

    /**
     * This endpoint is used to top-up an eSIM with the previously associated destination by providing an existing ICCID and the package details. The top-up is not feasible for eSIMs in "DELETED" or "ERROR" state.
     */
    public function topUpESIM(TopUpEsimRequest $input): TopUpEsimOkResponse
    {
        $data = $this->sendRequest('post', '/purchases/topup', ['json' => Serializer::serialize($input)]);

        return Serializer::deserialize($data, TopUpEsimOkResponse::class);
    }

    /**
     * This endpoint allows you to modify the dates of an existing package with a future activation start time. Editing can only be performed for packages that have not been activated, and it cannot change the package size. The modification must not change the package duration category to ensure pricing consistency.
     */
    public function editPurchase(EditPurchaseRequest $input): EditPurchaseOkResponse
    {
        $data = $this->sendRequest('post', '/purchases/edit', ['json' => Serializer::serialize($input)]);

        return Serializer::deserialize($data, EditPurchaseOkResponse::class);
    }

    /**
     * This endpoint can be called for consumption notifications (e.g. every 1 hour or when the user clicks a button). It returns the data balance (consumption) of purchased packages.
     */
    public function getPurchaseConsumption(string $purchaseId): GetPurchaseConsumptionOkResponse
    {
        $data = $this->sendRequest('get', "/purchases/{$purchaseId}/consumption", []);

        return Serializer::deserialize($data, GetPurchaseConsumptionOkResponse::class);
    }
}
