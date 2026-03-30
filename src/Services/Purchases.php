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
class Purchases extends BaseService
{
    /**
     * This endpoint is used to purchase a new eSIM by providing the package details.
     * @return array
     */
    public function createPurchaseV2(Models\CreatePurchaseV2Request $input): array
    {
        $response = $this->sendRequest('post', '/purchases/v2', [
            'json' => Serializer::serialize($input),
            'scopes' => [],
        ]);
        $data = $response->getBody()->getContents();

        return json_decode($data, true);
    }

    /**
     * This endpoint can be used to list all the successful purchases made between a given interval.
     * @return Models\ListPurchasesOkResponse
     */
    public function listPurchases(
        string $purchaseId = null,
        string $iccid = null,
        string $afterDate = null,
        string $beforeDate = null,
        string $email = null,
        string $referenceId = null,
        string $afterCursor = null,
        float $limit = null,
        float $after = null,
        float $before = null
    ): Models\ListPurchasesOkResponse {
        $response = $this->sendRequest('get', '/purchases', [
            'query' => [
                'purchaseId' => $purchaseId,
                'iccid' => $iccid,
                'afterDate' => $afterDate,
                'beforeDate' => $beforeDate,
                'email' => $email,
                'referenceId' => $referenceId,
                'afterCursor' => $afterCursor,
                'limit' => $limit,
                'after' => $after,
                'before' => $before,
            ],
            'scopes' => [],
        ]);
        $data = $response->getBody()->getContents();

        return Serializer::deserialize($data, Models\ListPurchasesOkResponse::class);
    }

    /**
     * This endpoint is used to purchase a new eSIM by providing the package details.
     * @return Models\CreatePurchaseOkResponse
     */
    public function createPurchase(Models\CreatePurchaseRequest $input): Models\CreatePurchaseOkResponse
    {
        $response = $this->sendRequest('post', '/purchases', ['json' => Serializer::serialize($input), 'scopes' => []]);
        $data = $response->getBody()->getContents();

        return Serializer::deserialize($data, Models\CreatePurchaseOkResponse::class);
    }

    /**
     * This endpoint is used to top-up an existing eSIM with the previously associated destination by providing its ICCID and package details. To determine if an eSIM can be topped up, use the Get eSIM endpoint, which returns the `isTopUpAllowed` flag.
     * @return Models\TopUpEsimOkResponse
     */
    public function topUpEsim(Models\TopUpEsimRequest $input): Models\TopUpEsimOkResponse
    {
        $response = $this->sendRequest('post', '/purchases/topup', [
            'json' => Serializer::serialize($input),
            'scopes' => [],
        ]);
        $data = $response->getBody()->getContents();

        return Serializer::deserialize($data, Models\TopUpEsimOkResponse::class);
    }

    /**
     * This endpoint allows you to modify the validity dates of an existing purchase.  

**Behavior:**
- If the purchase has **not yet been activated**, both the start and end dates can be updated.  
- If the purchase is **already active**, only the **end date** can be updated, while the **start date must remain unchanged** (and should be passed as originally set).  
- Updates must comply with the same pricing structure; the modification cannot alter the package size or change its duration category.  

The end date can be extended or shortened as long as it adheres to the same pricing category and does not exceed the allowed duration limits.

     * @return Models\EditPurchaseOkResponse
     */
    public function editPurchase(Models\EditPurchaseRequest $input): Models\EditPurchaseOkResponse
    {
        $response = $this->sendRequest('post', '/purchases/edit', [
            'json' => Serializer::serialize($input),
            'scopes' => [],
        ]);
        $data = $response->getBody()->getContents();

        return Serializer::deserialize($data, Models\EditPurchaseOkResponse::class);
    }

    /**
     * This endpoint can be called for consumption notifications (e.g. every 1 hour or when the user clicks a button). It returns the data balance (consumption) of purchased packages.
     * @return Models\GetPurchaseConsumptionOkResponse
     */
    public function getPurchaseConsumption(string $purchaseId): Models\GetPurchaseConsumptionOkResponse
    {
        $response = $this->sendRequest('get', "/purchases/{$purchaseId}/consumption", ['scopes' => []]);
        $data = $response->getBody()->getContents();

        return Serializer::deserialize($data, Models\GetPurchaseConsumptionOkResponse::class);
    }
}
