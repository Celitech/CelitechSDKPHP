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
class Device extends BaseService
{
  /** @var array|null Method-level configuration for getESimDevice */
  protected ?array $getESimDeviceConfig = null;

  /**
   * Set method-level configuration for getESimDevice.
   *
   * @param array $config Configuration overrides for this method
   * @return $this
   */
  public function setGetESimDeviceConfig(array $config): static
  {
    $this->getESimDeviceConfig = $config;
    return $this;
  }

  /**
   * Get eSIM Device
   *
   * @param string $iccid
   * @param string $accept
   * @return mixed
   */
  public function getESimDevice(string $iccid, string $accept, array $requestConfig = []): mixed
  {
    $resolvedConfig = $this->getResolvedConfig($this->getESimDeviceConfig, $requestConfig);
    $response = $this->sendRequest(
      'get',
      "/esim/{$iccid}/device",
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
