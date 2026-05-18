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
class IFrame extends BaseService
{
  /** @var array|null Method-level configuration for token */
  protected ?array $tokenConfig = null;

  /**
   * Set method-level configuration for token.
   *
   * @param array $config Configuration overrides for this method
   * @return $this
   */
  public function setTokenConfig(array $config): static
  {
    $this->tokenConfig = $config;
    return $this;
  }

  /**
   * Generate a new token to be used in the iFrame
   * @return Models\TokenOkResponse
   */
  public function token(array $requestConfig = []): Models\TokenOkResponse
  {
    $resolvedConfig = $this->getResolvedConfig($this->tokenConfig, $requestConfig);
    $response = $this->sendRequest('post', '/iframe/token', ['scopes' => []], $resolvedConfig);
    $data = $response->getBody()->getContents();

    $result = Serializer::deserialize($data, Models\TokenOkResponse::class);

    if ($resolvedConfig['enableResponseValidation'] ?? false) {
      $result?->validate();
    }
    return $result;
  }
}
