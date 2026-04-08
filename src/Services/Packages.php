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
class Packages extends BaseService
{
  /** @var array|null Method-level configuration for listPackages */
  protected ?array $listPackagesConfig = null;

  /**
   * Set method-level configuration for listPackages.
   *
   * @param array $config Configuration overrides for this method
   * @return $this
   */
  public function setListPackagesConfig(array $config): static
  {
    $this->listPackagesConfig = $config;
    return $this;
  }

  /**
   * List Packages
   * @return Models\ListPackagesOkResponse
   */
  public function listPackages(
    ?string $destination = null,
    ?string $startDate = null,
    ?string $endDate = null,
    ?string $afterCursor = null,
    ?float $limit = null,
    ?int $startTime = null,
    ?int $endTime = null,
    array $requestConfig = []
  ): Models\ListPackagesOkResponse {
    $resolvedConfig = $this->getResolvedConfig($this->listPackagesConfig, $requestConfig);
    $response = $this->sendRequest(
      'get',
      '/packages',
      [
        'query' => [
          'destination' => $destination,
          'startDate' => $startDate,
          'endDate' => $endDate,
          'afterCursor' => $afterCursor,
          'limit' => $limit,
          'startTime' => $startTime,
          'endTime' => $endTime
        ],
        'scopes' => []
      ],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    return Serializer::deserialize($data, Models\ListPackagesOkResponse::class);
  }
}
