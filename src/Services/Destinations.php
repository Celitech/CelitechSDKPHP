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
class Destinations extends BaseService
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
   * @return Models\ListDestinationsOkResponse
   */
  public function listDestinations(array $requestConfig = []): Models\ListDestinationsOkResponse
  {
    $resolvedConfig = $this->getResolvedConfig($this->listDestinationsConfig, $requestConfig);
    $response = $this->sendRequest('get', '/destinations', ['scopes' => []], $resolvedConfig);
    $data = $response->getBody()->getContents();

    $result = Serializer::deserialize($data, Models\ListDestinationsOkResponse::class);

    if ($resolvedConfig['enableResponseValidation'] ?? false) {
      $result?->validate();
    }
    return $result;
  }
}
