<?php

declare(strict_types=1);

namespace Celitech\Destinations;

use Celitech\Services\BaseService;
use Celitech\Utils\Serializer;

/**
 * Service class containing API endpoint methods.
 *
 * This class extends the base service to provide typed methods for specific API operations.
 * Each method corresponds to an API endpoint and handles request serialization,
 * execution, and response deserialization.
 */
class DestinationsClient extends BaseService
{
  /** @var array|null Method-level configuration for listDestinations */
  protected ?array $listDestinationsConfig = null;

  /**
   * Set method-level configuration for listDestinations.
   *
   * @param array $config Configuration overrides for this method
   * @return $this
   */
  public function setListDestinationsConfig(array $config): static
  {
    $this->listDestinationsConfig = $config;
    return $this;
  }

  /**
   * List Destinations
   *
   * @param ?array{
   *   baseUrl?: string,
   *   maxRetries?: int,
   *   timeout?: float,
   *   headers?: array<string, string>,
   *   queryParameters?: array<string, mixed>,
   *   bodyProperties?: array<string, mixed>,
   * } $options
   * @return ?\Celitech\Destinations\Types\ListDestinationsResponse
   * @throws \Celitech\Exceptions\CelitechException
   * @throws \Celitech\Exceptions\CelitechApiException
   */
  public function listDestinations(
    ?array $options = null
  ): ?\Celitech\Destinations\Types\ListDestinationsResponse {
    $resolvedConfig = $this->getResolvedConfig($this->listDestinationsConfig, $options);
    $response = $this->sendRequest('get', '/destinations', ['scopes' => []], $resolvedConfig);
    $data = $response->getBody()->getContents();

    try {
      $result = Serializer::deserialize(
        $data,
        \Celitech\Destinations\Types\ListDestinationsResponse::class
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
