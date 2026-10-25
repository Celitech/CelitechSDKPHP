<?php

declare(strict_types=1);

namespace Celitech\Purchases;

use Celitech\Services\BaseService;
use Celitech\Utils\Serializer;
use Celitech\Utils\Validator;

/**
 * Service class containing API endpoint methods.
 *
 * This class extends the base service to provide typed methods for specific API operations.
 * Each method corresponds to an API endpoint and handles request serialization,
 * execution, and response deserialization.
 */
class PurchasesClient extends BaseService
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
   * @param \Celitech\Purchases\Requests\CreatePurchaseV2Request $request Request parameters
   * @param ?array{
   *   baseUrl?: string,
   *   maxRetries?: int,
   *   timeout?: float,
   *   headers?: array<string, string>,
   *   queryParameters?: array<string, mixed>,
   *   bodyProperties?: array<string, mixed>,
   * } $options
   * @return ?array<\Celitech\Purchases\Types\CreatePurchaseV2ResponseItem>
   * @throws \Celitech\Exceptions\CelitechException
   * @throws \Celitech\Exceptions\CelitechApiException
   */
  public function createPurchaseV2(
    \Celitech\Purchases\Requests\CreatePurchaseV2Request $request,
    ?array $options = null
  ): ?array {
    $idempotencyKey = $request->idempotencyKey;

    Validator::validateString($idempotencyKey, 'Idempotency-Key', minLength: 1, maxLength: 255);
    $request->validate();

    $resolvedConfig = $this->getResolvedConfig($this->createPurchaseV2Config, $options);
    $response = $this->sendRequest(
      'post',
      '/purchases/v2',
      [
        'json' => $request,
        'headers' => [
          'Idempotency-Key' => $idempotencyKey
        ],
        'scopes' => []
      ],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    try {
      $result = Serializer::deserialize(
        $data,
        \Celitech\Purchases\Types\CreatePurchaseV2ResponseItem::class . '[]'
      );
    } catch (\JsonException | \TypeError | \InvalidArgumentException $e) {
      throw new \Celitech\Exceptions\CelitechException(
        message: "Failed to deserialize the response: {$e->getMessage()}",
        previous: $e
      );
    }
    return $result;
  }

  /**
   * This endpoint can be used to list all the successful purchases made between a given interval.
   *
   * @param \Celitech\Purchases\Requests\ListPurchasesRequest $request Request parameters
   * @param ?array{
   *   baseUrl?: string,
   *   maxRetries?: int,
   *   timeout?: float,
   *   headers?: array<string, string>,
   *   queryParameters?: array<string, mixed>,
   *   bodyProperties?: array<string, mixed>,
   * } $options
   * @return ?\Celitech\Purchases\Types\ListPurchasesResponse
   * @throws \Celitech\Exceptions\CelitechException
   * @throws \Celitech\Exceptions\CelitechApiException
   */
  public function listPurchases(
    \Celitech\Purchases\Requests\ListPurchasesRequest $request = new \Celitech\Purchases\Requests\ListPurchasesRequest(),
    ?array $options = null
  ): ?\Celitech\Purchases\Types\ListPurchasesResponse {
    $purchaseId = $request->purchaseId;
    $iccid = $request->iccid;
    $afterDate = $request->afterDate;
    $afterDate = $afterDate !== null ? Serializer::serializeDate($afterDate) : null;
    $beforeDate = $request->beforeDate;
    $beforeDate = $beforeDate !== null ? Serializer::serializeDate($beforeDate) : null;
    $email = $request->email;
    $referenceId = $request->referenceId;
    $afterCursor = $request->afterCursor;
    $limit = $request->limit;
    $after = $request->after;
    $before = $request->before;

    Validator::validateString($iccid, 'iccid', minLength: 18, maxLength: 22);

    $resolvedConfig = $this->getResolvedConfig($this->listPurchasesConfig, $options);
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

    try {
      $result = Serializer::deserialize(
        $data,
        \Celitech\Purchases\Types\ListPurchasesResponse::class
      );
    } catch (\JsonException | \TypeError | \InvalidArgumentException $e) {
      throw new \Celitech\Exceptions\CelitechException(
        message: "Failed to deserialize the response: {$e->getMessage()}",
        previous: $e
      );
    }
    return $result;
  }

  /**
   * This endpoint is used to purchase a new eSIM by providing the package details.
   *
   * @param \Celitech\Purchases\Requests\CreatePurchaseRequest $request Request parameters
   * @param ?array{
   *   baseUrl?: string,
   *   maxRetries?: int,
   *   timeout?: float,
   *   headers?: array<string, string>,
   *   queryParameters?: array<string, mixed>,
   *   bodyProperties?: array<string, mixed>,
   * } $options
   * @return ?\Celitech\Purchases\Types\CreatePurchaseResponse
   * @throws \Celitech\Exceptions\CelitechException
   * @throws \Celitech\Exceptions\CelitechApiException
   */
  public function createPurchase(
    \Celitech\Purchases\Requests\CreatePurchaseRequest $request,
    ?array $options = null
  ): ?\Celitech\Purchases\Types\CreatePurchaseResponse {
    $idempotencyKey = $request->idempotencyKey;

    Validator::validateString($idempotencyKey, 'Idempotency-Key', minLength: 1, maxLength: 255);
    $request->validate();

    $resolvedConfig = $this->getResolvedConfig($this->createPurchaseConfig, $options);
    $response = $this->sendRequest(
      'post',
      '/purchases',
      [
        'json' => $request,
        'headers' => [
          'Idempotency-Key' => $idempotencyKey
        ],
        'scopes' => []
      ],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    try {
      $result = Serializer::deserialize(
        $data,
        \Celitech\Purchases\Types\CreatePurchaseResponse::class
      );
    } catch (\JsonException | \TypeError | \InvalidArgumentException $e) {
      throw new \Celitech\Exceptions\CelitechException(
        message: "Failed to deserialize the response: {$e->getMessage()}",
        previous: $e
      );
    }
    return $result;
  }

  /**
   * This endpoint is used to top-up an existing eSIM with the previously associated destination by providing its ICCID and package details. To determine if an eSIM can be topped up, use the Get eSIM endpoint, which returns the `isTopUpAllowed` flag.
   *
   * @param \Celitech\Purchases\Requests\TopUpEsimRequest $request Request parameters
   * @param ?array{
   *   baseUrl?: string,
   *   maxRetries?: int,
   *   timeout?: float,
   *   headers?: array<string, string>,
   *   queryParameters?: array<string, mixed>,
   *   bodyProperties?: array<string, mixed>,
   * } $options
   * @return ?\Celitech\Purchases\Types\TopUpEsimResponse
   * @throws \Celitech\Exceptions\CelitechException
   * @throws \Celitech\Exceptions\CelitechApiException
   */
  public function topUpEsim(
    \Celitech\Purchases\Requests\TopUpEsimRequest $request,
    ?array $options = null
  ): ?\Celitech\Purchases\Types\TopUpEsimResponse {
    $idempotencyKey = $request->idempotencyKey;

    Validator::validateString($idempotencyKey, 'Idempotency-Key', minLength: 1, maxLength: 255);
    $request->validate();

    $resolvedConfig = $this->getResolvedConfig($this->topUpEsimConfig, $options);
    $response = $this->sendRequest(
      'post',
      '/purchases/topup',
      [
        'json' => $request,
        'headers' => [
          'Idempotency-Key' => $idempotencyKey
        ],
        'scopes' => []
      ],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    try {
      $result = Serializer::deserialize($data, \Celitech\Purchases\Types\TopUpEsimResponse::class);
    } catch (\JsonException | \TypeError | \InvalidArgumentException $e) {
      throw new \Celitech\Exceptions\CelitechException(
        message: "Failed to deserialize the response: {$e->getMessage()}",
        previous: $e
      );
    }
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
   * @param \Celitech\Purchases\Requests\EditPurchaseRequest $request Request parameters
   * @param ?array{
   *   baseUrl?: string,
   *   maxRetries?: int,
   *   timeout?: float,
   *   headers?: array<string, string>,
   *   queryParameters?: array<string, mixed>,
   *   bodyProperties?: array<string, mixed>,
   * } $options
   * @return ?\Celitech\Purchases\Types\EditPurchaseResponse
   * @throws \Celitech\Exceptions\CelitechException
   * @throws \Celitech\Exceptions\CelitechApiException
   */
  public function editPurchase(
    \Celitech\Purchases\Requests\EditPurchaseRequest $request,
    ?array $options = null
  ): ?\Celitech\Purchases\Types\EditPurchaseResponse {
    $idempotencyKey = $request->idempotencyKey;

    Validator::validateString($idempotencyKey, 'Idempotency-Key', minLength: 1, maxLength: 255);
    $request->validate();

    $resolvedConfig = $this->getResolvedConfig($this->editPurchaseConfig, $options);
    $response = $this->sendRequest(
      'post',
      '/purchases/edit',
      [
        'json' => $request,
        'headers' => [
          'Idempotency-Key' => $idempotencyKey
        ],
        'scopes' => []
      ],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    try {
      $result = Serializer::deserialize(
        $data,
        \Celitech\Purchases\Types\EditPurchaseResponse::class
      );
    } catch (\JsonException | \TypeError | \InvalidArgumentException $e) {
      throw new \Celitech\Exceptions\CelitechException(
        message: "Failed to deserialize the response: {$e->getMessage()}",
        previous: $e
      );
    }
    return $result;
  }

  /**
   * This endpoint can be called for consumption notifications (e.g. every 1 hour or when the user clicks a button). It returns the data balance (consumption) of purchased packages.
   *
   * @param string $purchaseId ID of the purchase
   * @param ?array{
   *   baseUrl?: string,
   *   maxRetries?: int,
   *   timeout?: float,
   *   headers?: array<string, string>,
   *   queryParameters?: array<string, mixed>,
   *   bodyProperties?: array<string, mixed>,
   * } $options
   * @return ?\Celitech\Purchases\Types\GetPurchaseConsumptionResponse
   * @throws \Celitech\Exceptions\CelitechException
   * @throws \Celitech\Exceptions\CelitechApiException
   */
  public function getPurchaseConsumption(
    string $purchaseId,
    ?array $options = null
  ): ?\Celitech\Purchases\Types\GetPurchaseConsumptionResponse {
    $resolvedConfig = $this->getResolvedConfig($this->getPurchaseConsumptionConfig, $options);
    $response = $this->sendRequest(
      'get',
      '/purchases/' .
        \Celitech\Core\Client\RawClient::encodePathParam($purchaseId) .
        '/consumption',
      ['scopes' => []],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    try {
      $result = Serializer::deserialize(
        $data,
        \Celitech\Purchases\Types\GetPurchaseConsumptionResponse::class
      );
    } catch (\JsonException | \TypeError | \InvalidArgumentException $e) {
      throw new \Celitech\Exceptions\CelitechException(
        message: "Failed to deserialize the response: {$e->getMessage()}",
        previous: $e
      );
    }
    return $result;
  }
}
