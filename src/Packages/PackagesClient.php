<?php

declare(strict_types=1);

namespace Celitech\Packages;

use Celitech\Services\BaseService;
use Celitech\Utils\Serializer;

/**
 * Service class containing API endpoint methods.
 *
 * This class extends the base service to provide typed methods for specific API operations.
 * Each method corresponds to an API endpoint and handles request serialization,
 * execution, and response deserialization.
 */
class PackagesClient extends BaseService
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
   *
   * @param \Celitech\Packages\Requests\ListPackagesRequest $request Request parameters
   * @param ?array{
   *   baseUrl?: string,
   *   maxRetries?: int,
   *   timeout?: float,
   *   headers?: array<string, string>,
   *   queryParameters?: array<string, mixed>,
   *   bodyProperties?: array<string, mixed>,
   * } $options
   * @return ?\Celitech\Packages\Types\ListPackagesResponse
   * @throws \Celitech\Exceptions\CelitechException
   * @throws \Celitech\Exceptions\CelitechApiException
   */
  public function listPackages(
    \Celitech\Packages\Requests\ListPackagesRequest $request = new \Celitech\Packages\Requests\ListPackagesRequest(),
    ?array $options = null
  ): ?\Celitech\Packages\Types\ListPackagesResponse {
    $destination = $request->destination;
    $dataLimitInGb = $request->dataLimitInGb;
    $startDate = $request->startDate;
    $startDate = $startDate !== null ? Serializer::serializeDate($startDate) : null;
    $endDate = $request->endDate;
    $endDate = $endDate !== null ? Serializer::serializeDate($endDate) : null;
    $afterCursor = $request->afterCursor;
    $limit = $request->limit;
    $startTime = $request->startTime;
    $endTime = $request->endTime;
    $includeUnlimited = $request->includeUnlimited;

    $resolvedConfig = $this->getResolvedConfig($this->listPackagesConfig, $options);
    $response = $this->sendRequest(
      'get',
      '/packages',
      [
        'query' => [
          'destination' => $destination,
          'dataLimitInGB' => $dataLimitInGb,
          'startDate' => $startDate,
          'endDate' => $endDate,
          'afterCursor' => $afterCursor,
          'limit' => $limit,
          'startTime' => $startTime,
          'endTime' => $endTime,
          'includeUnlimited' => $includeUnlimited
        ],
        'scopes' => []
      ],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    try {
      $result = Serializer::deserialize(
        $data,
        \Celitech\Packages\Types\ListPackagesResponse::class
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
