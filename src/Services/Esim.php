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
class Esim extends BaseService
{
  /** @var array|null Method-level configuration for getESim */
  protected ?array $getESimConfig = null;

  /**
   * Set method-level configuration for getESim.
   *
   * @param array $config Configuration overrides for this method
   * @return $this
   */
  public function setGetESimConfig(array $config): static
  {
    $this->getESimConfig = $config;
    return $this;
  }

  /**
   * Get eSIM
   *
   * @param string $accept
   * @param ?string $iccid ID of the eSIM
   * @return mixed
   */
  public function getESim(string $accept, ?string $iccid = null, array $requestConfig = []): mixed
  {
    $resolvedConfig = $this->getResolvedConfig($this->getESimConfig, $requestConfig);
    $response = $this->sendRequest(
      'get',
      '/esim',
      [
        'headers' => [
          'Accept' => $accept
        ],
        'query' => [
          'iccid' => $iccid
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
