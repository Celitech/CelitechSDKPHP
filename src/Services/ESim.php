<?php

declare(strict_types=1);

namespace Celitech\Services;

use Celitech\Utils\Serializer;
use Celitech\Utils\Validator;
use Celitech\Models;

/**
 * Service class containing API endpoint methods.
 *
 * This class extends the base service to provide typed methods for specific API operations.
 * Each method corresponds to an API endpoint and handles request serialization,
 * execution, and response deserialization.
 */
class ESim extends BaseService
{
  /** @var array|null Method-level configuration for getEsim */
  protected ?array $getEsimConfig = null;

  /** @var array|null Method-level configuration for getEsimDevice */
  protected ?array $getEsimDeviceConfig = null;

  /** @var array|null Method-level configuration for getEsimHistory */
  protected ?array $getEsimHistoryConfig = null;

  /**
   * Set method-level configuration for getEsim.
   *
   * @param array $config Configuration overrides for this method
   * @return $this
   */
  public function setGetEsimConfig(array $config): static
  {
    $this->getEsimConfig = $config;
    return $this;
  }

  /**
   * Set method-level configuration for getEsimDevice.
   *
   * @param array $config Configuration overrides for this method
   * @return $this
   */
  public function setGetEsimDeviceConfig(array $config): static
  {
    $this->getEsimDeviceConfig = $config;
    return $this;
  }

  /**
   * Set method-level configuration for getEsimHistory.
   *
   * @param array $config Configuration overrides for this method
   * @return $this
   */
  public function setGetEsimHistoryConfig(array $config): static
  {
    $this->getEsimHistoryConfig = $config;
    return $this;
  }

  /**
   * Get eSIM
   *
   * @param string $iccid ID of the eSIM
   * @return Models\GetEsimOkResponse
   */
  public function getEsim(string $iccid, array $requestConfig = []): Models\GetEsimOkResponse
  {
    Validator::validateString($$iccid, 'iccid', minLength: 18, maxLength: 22);

    $resolvedConfig = $this->getResolvedConfig($this->getEsimConfig, $requestConfig);
    $response = $this->sendRequest(
      'get',
      '/esim',
      [
        'query' => [
          'iccid' => $iccid
        ],
        'scopes' => []
      ],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    $result = Serializer::deserialize($data, Models\GetEsimOkResponse::class);

    if ($resolvedConfig['enableResponseValidation'] ?? false) {
      $result?->validate();
    }
    return $result;
  }

  /**
   * Get eSIM Device
   *
   * @param string $iccid ID of the eSIM
   * @return Models\GetEsimDeviceOkResponse
   */
  public function getEsimDevice(
    string $iccid,
    array $requestConfig = []
  ): Models\GetEsimDeviceOkResponse {
    Validator::validateString($$iccid, 'iccid', minLength: 18, maxLength: 22);

    $resolvedConfig = $this->getResolvedConfig($this->getEsimDeviceConfig, $requestConfig);
    $response = $this->sendRequest(
      'get',
      "/esim/{$iccid}/device",
      ['scopes' => []],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    $result = Serializer::deserialize($data, Models\GetEsimDeviceOkResponse::class);

    if ($resolvedConfig['enableResponseValidation'] ?? false) {
      $result?->validate();
    }
    return $result;
  }

  /**
   * Get eSIM History
   *
   * @param string $iccid ID of the eSIM
   * @return Models\GetEsimHistoryOkResponse
   */
  public function getEsimHistory(
    string $iccid,
    array $requestConfig = []
  ): Models\GetEsimHistoryOkResponse {
    Validator::validateString($$iccid, 'iccid', minLength: 18, maxLength: 22);

    $resolvedConfig = $this->getResolvedConfig($this->getEsimHistoryConfig, $requestConfig);
    $response = $this->sendRequest(
      'get',
      "/esim/{$iccid}/history",
      ['scopes' => []],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    $result = Serializer::deserialize($data, Models\GetEsimHistoryOkResponse::class);

    if ($resolvedConfig['enableResponseValidation'] ?? false) {
      $result?->validate();
    }
    return $result;
  }
}
