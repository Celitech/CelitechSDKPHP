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
class V2 extends BaseService
{
  /** @var array|null Method-level configuration for createPurchaseV2 */
  protected ?array $createPurchaseV2Config = null;

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
   * This endpoint is used to purchase a new eSIM by providing the package details.
   *
   * @param Models\CreatePurchaseV2Request $input Request body
   * @param string $accept
   * @return mixed
   */
  public function createPurchaseV2(
    Models\CreatePurchaseV2Request $input,
    string $accept,
    array $requestConfig = []
  ): mixed {
    $resolvedConfig = $this->getResolvedConfig($this->createPurchaseV2Config, $requestConfig);
    $response = $this->sendRequest(
      'post',
      '/purchases/v2',
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
}
