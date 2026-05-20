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
class Topup extends BaseService
{
  /** @var array|null Method-level configuration for topUpESim */
  protected ?array $topUpESimConfig = null;

  /**
   * Set method-level configuration for topUpESim.
   *
   * @param array $config Configuration overrides for this method
   * @return $this
   */
  public function setTopUpESimConfig(array $config): static
  {
    $this->topUpESimConfig = $config;
    return $this;
  }

  /**
   * This endpoint is used to top-up an existing eSIM with the previously associated destination by providing its ICCID and package details. To determine if an eSIM can be topped up, use the Get eSIM endpoint, which returns the `isTopUpAllowed` flag.
   *
   * @param Models\TopUpESimRequest $input Request body
   * @param string $accept
   * @return mixed
   */
  public function topUpESim(
    Models\TopUpESimRequest $input,
    string $accept,
    array $requestConfig = []
  ): mixed {
    $resolvedConfig = $this->getResolvedConfig($this->topUpESimConfig, $requestConfig);
    $response = $this->sendRequest(
      'post',
      '/purchases/topup',
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
