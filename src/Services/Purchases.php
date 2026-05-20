<?php

declare(strict_types=1);

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
  /** @var array|null Method-level configuration for createPurchase */
  protected ?array $createPurchaseConfig = null;

  /** @var array|null Method-level configuration for listPurchases */
  protected ?array $listPurchasesConfig = null;

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
   * This endpoint is used to purchase a new eSIM by providing the package details.
   *
   * @param Models\CreatePurchaseRequest $input Request body
   * @param string $accept
   * @return mixed
   */
  public function createPurchase(
    Models\CreatePurchaseRequest $input,
    string $accept,
    array $requestConfig = []
  ): mixed {
    $resolvedConfig = $this->getResolvedConfig($this->createPurchaseConfig, $requestConfig);
    $response = $this->sendRequest(
      'post',
      '/purchases',
      [
        'json' => Serializer::serialize($input),
        'headers' => [
          'Accept' => $accept
        ],
        'scopes' => []
      ],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    $result = $this->decodeJson($data);

    return $result;
  }

  /**
   * This endpoint can be used to list all the successful purchases made between a given interval.
   *
   * @param string $accept
   * @param ?string $purchaseId ID of the purchase
   * @param ?string $iccid ID of the eSIM
   * @param ?string $afterDate Start date of the interval for filtering purchases in the format 'yyyy-MM-dd'
   * @param ?string $beforeDate End date of the interval for filtering purchases in the format 'yyyy-MM-dd'
   * @param ?string $email Email associated to the purchase.
   * @param ?string $referenceId The referenceId that was provided by the partner during the purchase or topup flow.
   * @param ?string $afterCursor To get the next batch of results, use this parameter. It tells the API where to start fetching data after the last item you received. It helps you avoid repeats and efficiently browse through large sets of data.
   * @param ?string $limit Maximum number of purchases to be returned in the response. The value must be greater than 0 and less than or equal to 100. If not provided, the default value is 20
   * @param ?string $after Epoch value representing the start of the time interval for filtering purchases
   * @param ?string $before Epoch value representing the end of the time interval for filtering purchases
   * @return mixed
   */
  public function listPurchases(
    string $accept,
    ?string $purchaseId = null,
    ?string $iccid = null,
    ?string $afterDate = null,
    ?string $beforeDate = null,
    ?string $email = null,
    ?string $referenceId = null,
    ?string $afterCursor = null,
    ?string $limit = null,
    ?string $after = null,
    ?string $before = null,
    array $requestConfig = []
  ): mixed {
    $resolvedConfig = $this->getResolvedConfig($this->listPurchasesConfig, $requestConfig);
    $response = $this->sendRequest(
      'get',
      '/purchases',
      [
        'headers' => [
          'Accept' => $accept
        ],
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

    $result = $this->decodeJson($data);

    return $result;
  }
}
