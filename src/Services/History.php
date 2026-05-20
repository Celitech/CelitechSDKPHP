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
class History extends BaseService
{
  /** @var array|null Method-level configuration for getESimHistory */
  protected ?array $getESimHistoryConfig = null;

  /**
   * Set method-level configuration for getESimHistory.
   *
   * @param array $config Configuration overrides for this method
   * @return $this
   */
  public function setGetESimHistoryConfig(array $config): static
  {
    $this->getESimHistoryConfig = $config;
    return $this;
  }

  /**
   * Get eSIM History
   *
   * @param string $iccid
   * @param string $accept
   * @return mixed
   */
  public function getESimHistory(string $iccid, string $accept, array $requestConfig = []): mixed
  {
    $resolvedConfig = $this->getResolvedConfig($this->getESimHistoryConfig, $requestConfig);
    $response = $this->sendRequest(
      'get',
      "/esim/{$iccid}/history",
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
