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

  /** @var array|null Method-level configuration for suspendEsim */
  protected ?array $suspendEsimConfig = null;

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
   * Set method-level configuration for suspendEsim.
   *
   * @param array $config Configuration overrides for this method
   * @return $this
   */
  public function setSuspendEsimConfig(array $config): static
  {
    $this->suspendEsimConfig = $config;
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
    Validator::validateString($iccid, 'iccid', minLength: 18, maxLength: 22);

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

    $result?->validate();
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
    Validator::validateString($iccid, 'iccid', minLength: 18, maxLength: 22);

    $resolvedConfig = $this->getResolvedConfig($this->getEsimDeviceConfig, $requestConfig);
    $response = $this->sendRequest(
      'get',
      "/esim/{$this->encodePathParam($iccid)}/device",
      ['scopes' => []],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    $result = Serializer::deserialize($data, Models\GetEsimDeviceOkResponse::class);

    $result?->validate();
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
    Validator::validateString($iccid, 'iccid', minLength: 18, maxLength: 22);

    $resolvedConfig = $this->getResolvedConfig($this->getEsimHistoryConfig, $requestConfig);
    $response = $this->sendRequest(
      'get',
      "/esim/{$this->encodePathParam($iccid)}/history",
      ['scopes' => []],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    $result = Serializer::deserialize($data, Models\GetEsimHistoryOkResponse::class);

    $result?->validate();
    return $result;
  }

  /**
   * Suspends an eSIM to prevent abuse. All of the eSIM's packages are terminated and its connectivity is switched off. The eSIM is not refunded: the charge stays.
   *
   * @param string $iccid ID of the eSIM
   * @param ?string $idempotencyKey Optional. A unique value (for example a UUID) that makes this request safe to retry. If a request with the same `Idempotency-Key` and the same body succeeds, repeating it returns the original response instead of performing the action again, and the response includes the header `Idempotent-Replayed: true`. Keys are scoped to your credentials and remembered for 3 days. Only successful (2xx) responses are remembered, so a request that failed can be retried with the same key. Reusing a key with a different request returns `422`; sending it while the original request is still being processed returns `409`. If the idempotency store is temporarily unavailable, the request is not processed and returns `503`; retry it. Must be 1 to 255 printable ASCII characters, without spaces.
   * @return Models\SuspendEsimOkResponse
   */
  public function suspendEsim(
    string $iccid,
    ?string $idempotencyKey = null,
    array $requestConfig = []
  ): Models\SuspendEsimOkResponse {
    Validator::validateString($iccid, 'iccid', minLength: 18, maxLength: 22);
    Validator::validateString($idempotencyKey, 'Idempotency-Key', minLength: 1, maxLength: 255);

    $resolvedConfig = $this->getResolvedConfig($this->suspendEsimConfig, $requestConfig);
    $response = $this->sendRequest(
      'post',
      "/esim/{$this->encodePathParam($iccid)}/suspend",
      [
        'headers' => [
          'Idempotency-Key' => $idempotencyKey
        ],
        'scopes' => []
      ],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    $result = Serializer::deserialize($data, Models\SuspendEsimOkResponse::class);

    $result?->validate();
    return $result;
  }
}
