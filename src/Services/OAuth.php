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
class OAuth extends BaseService
{
  /** @var array|null Method-level configuration for getAccessToken */
  protected ?array $getAccessTokenConfig = null;

  /**
   * Set method-level configuration for getAccessToken.
   *
   * @param array $config Configuration overrides for this method
   * @return $this
   */
  public function setGetAccessTokenConfig(array $config): static
  {
    $this->getAccessTokenConfig = $config;
    return $this;
  }

  /**
   * This endpoint was added by liblab
   *
   * @param Models\GetAccessTokenRequest $input Request body
   * @return Models\GetAccessTokenOkResponse
   */
  public function getAccessToken(
    Models\GetAccessTokenRequest $input,
    array $requestConfig = []
  ): Models\GetAccessTokenOkResponse {
    $resolvedConfig = $this->getResolvedConfig($this->getAccessTokenConfig, $requestConfig);
    $response = $this->sendRequest(
      'post',
      '/oauth2/token',
      ['form_params' => Serializer::serialize($input)],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    $result = Serializer::deserialize($data, Models\GetAccessTokenOkResponse::class);

    if ($resolvedConfig['enableResponseValidation'] ?? false) {
      $result?->validate();
    }
    return $result;
  }
}
