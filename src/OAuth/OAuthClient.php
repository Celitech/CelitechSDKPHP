<?php

declare(strict_types=1);

namespace Celitech\OAuth;

use Celitech\Services\BaseService;
use Celitech\Utils\Serializer;

/**
 * Service class containing API endpoint methods.
 *
 * This class extends the base service to provide typed methods for specific API operations.
 * Each method corresponds to an API endpoint and handles request serialization,
 * execution, and response deserialization.
 */
class OAuthClient extends BaseService
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
   * @param \Celitech\OAuth\Requests\OAuthTokenRequest $request OAuth token request parameters
   * @param ?array{
   *   baseUrl?: string,
   *   maxRetries?: int,
   *   timeout?: float,
   *   headers?: array<string, string>,
   *   queryParameters?: array<string, mixed>,
   *   bodyProperties?: array<string, mixed>,
   * } $options
   * @return ?\Celitech\OAuth\Types\OAuthTokenResponse
   * @throws \Celitech\Exceptions\CelitechException
   * @throws \Celitech\Exceptions\CelitechApiException
   */
  public function getAccessToken(
    \Celitech\OAuth\Requests\OAuthTokenRequest $request,
    ?array $options = null
  ): ?\Celitech\OAuth\Types\OAuthTokenResponse {
    $request->validate();

    $resolvedConfig = $this->getResolvedConfig($this->getAccessTokenConfig, $options);
    $response = $this->sendRequest(
      'post',
      '/oauth2/token',
      ['form_params' => Serializer::serialize($request)],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    try {
      $result = Serializer::deserialize($data, \Celitech\OAuth\Types\OAuthTokenResponse::class);
    } catch (\JsonException | \TypeError | \InvalidArgumentException $e) {
      throw new \Celitech\Exceptions\CelitechException(
        message: "Failed to deserialize the response: {$e->getMessage()}",
        previous: $e
      );
    }
    return $result;
  }
}
