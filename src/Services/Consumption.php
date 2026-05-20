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
class Consumption extends BaseService
{
  /** @var array|null Method-level configuration for getPurchaseConsumption */
  protected ?array $getPurchaseConsumptionConfig = null;

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
   * This endpoint can be called for consumption notifications (e.g. every 1 hour or when the user clicks a button). It returns the data balance (consumption) of purchased packages.
   *
   * @param string $purchaseId
   * @param string $accept
   * @return mixed
   */
  public function getPurchaseConsumption(
    string $purchaseId,
    string $accept,
    array $requestConfig = []
  ): mixed {
    $resolvedConfig = $this->getResolvedConfig($this->getPurchaseConsumptionConfig, $requestConfig);
    $response = $this->sendRequest(
      'get',
      "/purchases/{$purchaseId}/consumption",
      [
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
}
