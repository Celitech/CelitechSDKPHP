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
class Token extends BaseService
{
  /** @var array|null Method-level configuration for generateToken */
  protected ?array $generateTokenConfig = null;

  /**
   * Set method-level configuration for generateToken.
   *
   * @param array $config Configuration overrides for this method
   * @return $this
   */
  public function setGenerateTokenConfig(array $config): static
  {
    $this->generateTokenConfig = $config;
    return $this;
  }

  /**
   * Generate a new token to be used in the iFrame
   *
   * @param string $accept
   * @return mixed
   */
  public function generateToken(string $accept, array $requestConfig = []): mixed
  {
    $resolvedConfig = $this->getResolvedConfig($this->generateTokenConfig, $requestConfig);
    $response = $this->sendRequest(
      'post',
      '/iframe/token',
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
