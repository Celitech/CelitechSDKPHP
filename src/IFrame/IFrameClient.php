<?php

declare(strict_types=1);

namespace Celitech\IFrame;

use Celitech\Services\BaseService;
use Celitech\Utils\Serializer;

/**
 * Service class containing API endpoint methods.
 *
 * This class extends the base service to provide typed methods for specific API operations.
 * Each method corresponds to an API endpoint and handles request serialization,
 * execution, and response deserialization.
 */
class IFrameClient extends BaseService
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
   *
   * @param ?array{
   *   baseUrl?: string,
   *   maxRetries?: int,
   *   timeout?: float,
   *   headers?: array<string, string>,
   *   queryParameters?: array<string, mixed>,
   *   bodyProperties?: array<string, mixed>,
   * } $options
   * @return ?\Celitech\IFrame\Types\TokenResponse
   * @throws \Celitech\Exceptions\CelitechException
   * @throws \Celitech\Exceptions\CelitechApiException
   */
  public function token(?array $options = null): ?\Celitech\IFrame\Types\TokenResponse
  {
    $resolvedConfig = $this->getResolvedConfig($this->tokenConfig, $options);
    $response = $this->sendRequest('post', '/iframe/token', ['scopes' => []], $resolvedConfig);
    $data = $response->getBody()->getContents();

    try {
      $result = Serializer::deserialize($data, \Celitech\IFrame\Types\TokenResponse::class);
    } catch (\JsonException | \TypeError | \InvalidArgumentException $e) {
      throw new \Celitech\Exceptions\CelitechException(
        message: "Failed to deserialize the response: {$e->getMessage()}",
        previous: $e
      );
    }
    return $result;
  }
}
