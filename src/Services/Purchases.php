<?php

declare(strict_types=1);

namespace Celitech\Services;

use Celitech\Utils\Serializer;
use Celitech\Utils\Validator;
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
  /** @var array|null Method-level configuration for createPurchaseV2 */
  protected ?array $createPurchaseV2Config = null;

  /** @var array|null Method-level configuration for listPurchases */
  protected ?array $listPurchasesConfig = null;

  /** @var array|null Method-level configuration for createPurchase */
  protected ?array $createPurchaseConfig = null;

  /** @var array|null Method-level configuration for topUpEsim */
  protected ?array $topUpEsimConfig = null;

  /** @var array|null Method-level configuration for editPurchase */
  protected ?array $editPurchaseConfig = null;

  /** @var array|null Method-level configuration for getPurchaseConsumption */
  protected ?array $getPurchaseConsumptionConfig = null;

  /**
   * Set method-level configuration for createPurchaseV2.
   *
   * @param array $config Configuration overrides for this method
   * @return $this
   */
  public function setCreatePurchaseV2Config(array $config): static
  {
    $this->createPurchaseV2Config = $config;
    return $this;
  }

  /**
   * Set method-level configuration for listPurchases.
   *
   * @param array $config Configuration overrides for this method
   * @return $this
   */
  public function setListPurchasesConfig(array $config): static
  {
    $this->listPurchasesConfig = $config;
    return $this;
  }

  /**
   * Set method-level configuration for createPurchase.
   *
   * @param array $config Configuration overrides for this method
   * @return $this
   */
  public function setCreatePurchaseConfig(array $config): static
  {
    $this->createPurchaseConfig = $config;
    return $this;
  }

  /**
   * Set method-level configuration for topUpEsim.
   *
   * @param array $config Configuration overrides for this method
   * @return $this
   */
  public function setTopUpEsimConfig(array $config): static
  {
    $this->topUpEsimConfig = $config;
    return $this;
  }

  /**
   * Set method-level configuration for editPurchase.
   *
   * @param array $config Configuration overrides for this method
   * @return $this
   */
  public function setEditPurchaseConfig(array $config): static
  {
    $this->editPurchaseConfig = $config;
    return $this;
  }

  /**
   * Set method-level configuration for getPurchaseConsumption.
   *
   * @param array $config Configuration overrides for this method
   * @return $this
   */
  public function setGetPurchaseConsumptionConfig(array $config): static
  {
    $this->getPurchaseConsumptionConfig = $config;
    return $this;
  }

  /**
   * This endpoint is used to purchase a new eSIM by providing the package details.
   *
   * @param Models\CreatePurchaseV2Request $input Request body
   * @param ?string $idempotencyKey Optional. A unique value (for example a UUID) that makes this request safe to retry. If a request with the same `Idempotency-Key` and the same body succeeds, repeating it returns the original response instead of performing the action again, and the response includes the header `Idempotent-Replayed: true`. Keys are scoped to your credentials and remembered for 3 days. Only successful (2xx) responses are remembered, so a request that failed can be retried with the same key. Reusing a key with a different request returns `422`; sending it while the original request is still being processed returns `409`. If the idempotency store is temporarily unavailable, the request is not processed and returns `503`; retry it. Must be 1 to 255 printable ASCII characters, without spaces.
   * @return array
   */
  public function createPurchaseV2(
    Models\CreatePurchaseV2Request $input,
    ?string $idempotencyKey = null,
    array $requestConfig = []
  ): array {
    Validator::validateString($idempotencyKey, 'Idempotency-Key', minLength: 1, maxLength: 255);
    $input->validate();

    $resolvedConfig = $this->getResolvedConfig($this->createPurchaseV2Config, $requestConfig);
    $response = $this->sendRequest(
      'post',
      '/purchases/v2',
      [
        'json' => $input,
        'headers' => [
          'Idempotency-Key' => $idempotencyKey
        ],
        'scopes' => []
      ],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    $result = Serializer::deserialize($data, Models\CreatePurchaseV2OkResponse::class . '[]');

    foreach ($result as $item) {
      $item?->validate();
    }
    return $result;
  }

  /**
   * This endpoint can be used to list all the successful purchases made between a given interval.
   *
   * @param ?string $purchaseId ID of the purchase
   * @param ?string $iccid ID of the eSIM
   * @param ?string $afterDate Start date of the interval for filtering purchases in the format 'yyyy-MM-dd'
   * @param ?string $beforeDate End date of the interval for filtering purchases in the format 'yyyy-MM-dd'
   * @param ?string $email Email associated to the purchase.
   * @param ?string $referenceId The referenceId that was provided by the partner during the purchase or topup flow.
   * @param ?string $afterCursor To get the next batch of results, use this parameter. It tells the API where to start fetching data after the last item you received. It helps you avoid repeats and efficiently browse through large sets of data.
   * @param ?float $limit Maximum number of purchases to be returned in the response. The value must be greater than 0 and less than or equal to 100. If not provided, the default value is 20
   * @param ?float $after Epoch value representing the start of the time interval for filtering purchases
   * @param ?float $before Epoch value representing the end of the time interval for filtering purchases
   * @return Models\ListPurchasesOkResponse
   */
  public function listPurchases(
    ?string $purchaseId = null,
    ?string $iccid = null,
    ?string $afterDate = null,
    ?string $beforeDate = null,
    ?string $email = null,
    ?string $referenceId = null,
    ?string $afterCursor = null,
    ?float $limit = null,
    ?float $after = null,
    ?float $before = null,
    array $requestConfig = []
  ): Models\ListPurchasesOkResponse {
    Validator::validateString($iccid, 'iccid', minLength: 18, maxLength: 22);

    $resolvedConfig = $this->getResolvedConfig($this->listPurchasesConfig, $requestConfig);
    $response = $this->sendRequest(
      'get',
      '/purchases',
      [
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
          'before' => $before
        ],
        'scopes' => []
      ],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    $result = Serializer::deserialize($data, Models\ListPurchasesOkResponse::class);

    $result?->validate();
    return $result;
  }

  /**
   * This endpoint is used to purchase a new eSIM by providing the package details.
   *
   * @param Models\CreatePurchaseRequest $input Request body
   * @param ?string $idempotencyKey Optional. A unique value (for example a UUID) that makes this request safe to retry. If a request with the same `Idempotency-Key` and the same body succeeds, repeating it returns the original response instead of performing the action again, and the response includes the header `Idempotent-Replayed: true`. Keys are scoped to your credentials and remembered for 3 days. Only successful (2xx) responses are remembered, so a request that failed can be retried with the same key. Reusing a key with a different request returns `422`; sending it while the original request is still being processed returns `409`. If the idempotency store is temporarily unavailable, the request is not processed and returns `503`; retry it. Must be 1 to 255 printable ASCII characters, without spaces.
   * @return Models\CreatePurchaseOkResponse
   */
  public function createPurchase(
    Models\CreatePurchaseRequest $input,
    ?string $idempotencyKey = null,
    array $requestConfig = []
  ): Models\CreatePurchaseOkResponse {
    Validator::validateString($idempotencyKey, 'Idempotency-Key', minLength: 1, maxLength: 255);
    $input->validate();

    $resolvedConfig = $this->getResolvedConfig($this->createPurchaseConfig, $requestConfig);
    $response = $this->sendRequest(
      'post',
      '/purchases',
      [
        'json' => $input,
        'headers' => [
          'Idempotency-Key' => $idempotencyKey
        ],
        'scopes' => []
      ],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    $result = Serializer::deserialize($data, Models\CreatePurchaseOkResponse::class);

    $result?->validate();
    return $result;
  }

  /**
   * This endpoint is used to top-up an existing eSIM with the previously associated destination by providing its ICCID and package details. To determine if an eSIM can be topped up, use the Get eSIM endpoint, which returns the `isTopUpAllowed` flag.
   *
   * @param Models\TopUpEsimRequest $input Request body
   * @param ?string $idempotencyKey Optional. A unique value (for example a UUID) that makes this request safe to retry. If a request with the same `Idempotency-Key` and the same body succeeds, repeating it returns the original response instead of performing the action again, and the response includes the header `Idempotent-Replayed: true`. Keys are scoped to your credentials and remembered for 3 days. Only successful (2xx) responses are remembered, so a request that failed can be retried with the same key. Reusing a key with a different request returns `422`; sending it while the original request is still being processed returns `409`. If the idempotency store is temporarily unavailable, the request is not processed and returns `503`; retry it. Must be 1 to 255 printable ASCII characters, without spaces.
   * @return Models\TopUpEsimOkResponse
   */
  public function topUpEsim(
    Models\TopUpEsimRequest $input,
    ?string $idempotencyKey = null,
    array $requestConfig = []
  ): Models\TopUpEsimOkResponse {
    Validator::validateString($idempotencyKey, 'Idempotency-Key', minLength: 1, maxLength: 255);
    $input->validate();

    $resolvedConfig = $this->getResolvedConfig($this->topUpEsimConfig, $requestConfig);
    $response = $this->sendRequest(
      'post',
      '/purchases/topup',
      [
        'json' => $input,
        'headers' => [
          'Idempotency-Key' => $idempotencyKey
        ],
        'scopes' => []
      ],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    $result = Serializer::deserialize($data, Models\TopUpEsimOkResponse::class);

    $result?->validate();
    return $result;
  }

  /**
   * This endpoint allows you to modify the validity dates of an existing purchase.
   *
   * **Behavior:**
   * - If the purchase has **not yet been activated**, both the start and end dates can be updated.
   * - If the purchase is **already active**, only the **end date** can be updated, while the **start date must remain unchanged** (and should be passed as originally set).
   * - Updates must comply with the same pricing structure; the modification cannot alter the package size or change its duration category.
   *
   * The end date can be extended or shortened as long as it adheres to the same pricing category and does not exceed the allowed duration limits.
   *
   *
   * @param Models\EditPurchaseRequest $input Request body
   * @param ?string $idempotencyKey Optional. A unique value (for example a UUID) that makes this request safe to retry. If a request with the same `Idempotency-Key` and the same body succeeds, repeating it returns the original response instead of performing the action again, and the response includes the header `Idempotent-Replayed: true`. Keys are scoped to your credentials and remembered for 3 days. Only successful (2xx) responses are remembered, so a request that failed can be retried with the same key. Reusing a key with a different request returns `422`; sending it while the original request is still being processed returns `409`. If the idempotency store is temporarily unavailable, the request is not processed and returns `503`; retry it. Must be 1 to 255 printable ASCII characters, without spaces.
   * @return Models\EditPurchaseOkResponse
   */
  public function editPurchase(
    Models\EditPurchaseRequest $input,
    ?string $idempotencyKey = null,
    array $requestConfig = []
  ): Models\EditPurchaseOkResponse {
    Validator::validateString($idempotencyKey, 'Idempotency-Key', minLength: 1, maxLength: 255);
    $input->validate();

    $resolvedConfig = $this->getResolvedConfig($this->editPurchaseConfig, $requestConfig);
    $response = $this->sendRequest(
      'post',
      '/purchases/edit',
      [
        'json' => $input,
        'headers' => [
          'Idempotency-Key' => $idempotencyKey
        ],
        'scopes' => []
      ],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    $result = Serializer::deserialize($data, Models\EditPurchaseOkResponse::class);

    $result?->validate();
    return $result;
  }

  /**
   * This endpoint can be called for consumption notifications (e.g. every 1 hour or when the user clicks a button). It returns the data balance (consumption) of purchased packages.
   *
   * @param string $purchaseId ID of the purchase
   * @return Models\GetPurchaseConsumptionOkResponse
   */
  public function getPurchaseConsumption(
    string $purchaseId,
    array $requestConfig = []
  ): Models\GetPurchaseConsumptionOkResponse {
    $resolvedConfig = $this->getResolvedConfig($this->getPurchaseConsumptionConfig, $requestConfig);
    $response = $this->sendRequest(
      'get',
      "/purchases/{$this->encodePathParam($purchaseId)}/consumption",
      ['scopes' => []],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    $result = Serializer::deserialize($data, Models\GetPurchaseConsumptionOkResponse::class);

    $result?->validate();
    return $result;
  }
}
