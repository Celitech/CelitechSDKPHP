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
   *
   * @param string $accept
   * @param ?string $destination ISO representation of the package's destination. Supports both ISO2 (e.g., 'FR') and ISO3 (e.g., 'FRA') country codes.
   * @param ?string $startDate Start date of the package's validity in the format 'yyyy-MM-dd'. This date can be set to the current day or any day within the next 12 months.
   * @param ?string $endDate End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date.
   * @param ?string $afterCursor To get the next batch of results, use this parameter. It tells the API where to start fetching data after the last item you received. It helps you avoid repeats and efficiently browse through large sets of data.
   * @param ?string $limit Maximum number of packages to be returned in the response. The value must be greater than 0 and less than or equal to 160. If not provided, the default value is 20
   * @param ?string $startTime Epoch value representing the start time of the package's validity. This timestamp can be set to the current time or any time within the next 12 months
   * @param ?string $endTime Epoch value representing the end time of the package's validity. End time can be maximum 90 days after Start time
   * @return mixed
   */
  public function listPackages(
    string $accept,
    ?string $destination = null,
    ?string $startDate = null,
    ?string $endDate = null,
    ?string $afterCursor = null,
    ?string $limit = null,
    ?string $startTime = null,
    ?string $endTime = null,
    array $requestConfig = []
  ): mixed {
    $resolvedConfig = $this->getResolvedConfig($this->listPackagesConfig, $requestConfig);
    $response = $this->sendRequest(
      'get',
      '/packages',
      [
        'headers' => [
          'Accept' => $accept
        ],
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

    $result = $this->decodeJson($data);

    return $result;
  }
}
