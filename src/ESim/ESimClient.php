<?php

declare(strict_types=1);

namespace Celitech\ESim;

use Celitech\Services\BaseService;
use Celitech\Utils\Serializer;
use Celitech\Utils\Validator;

/**
 * Service class containing API endpoint methods.
 *
 * This class extends the base service to provide typed methods for specific API operations.
 * Each method corresponds to an API endpoint and handles request serialization,
 * execution, and response deserialization.
 */
class ESimClient extends BaseService
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
   * @param \Celitech\ESim\Requests\GetEsimRequest $request Request parameters
   * @param ?array{
   *   baseUrl?: string,
   *   maxRetries?: int,
   *   timeout?: float,
   *   headers?: array<string, string>,
   *   queryParameters?: array<string, mixed>,
   *   bodyProperties?: array<string, mixed>,
   * } $options
   * @return ?\Celitech\ESim\Types\GetEsimResponse
   * @throws \Celitech\Exceptions\CelitechException
   * @throws \Celitech\Exceptions\CelitechApiException
   */
  public function getEsim(
    \Celitech\ESim\Requests\GetEsimRequest $request,
    ?array $options = null
  ): ?\Celitech\ESim\Types\GetEsimResponse {
    $iccid = $request->iccid;

    Validator::validateString($iccid, 'iccid', minLength: 18, maxLength: 22);

    $resolvedConfig = $this->getResolvedConfig($this->getEsimConfig, $options);
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

    try {
      $result = Serializer::deserialize($data, \Celitech\ESim\Types\GetEsimResponse::class);
    } catch (\JsonException | \TypeError | \InvalidArgumentException $e) {
      throw new \Celitech\Exceptions\CelitechException(
        message: "Failed to deserialize the response: {$e->getMessage()}",
        previous: $e
      );
    }
    return $result;
  }

  /**
   * Get eSIM Device
   *
   * @param string $iccid ID of the eSIM
   * @param ?array{
   *   baseUrl?: string,
   *   maxRetries?: int,
   *   timeout?: float,
   *   headers?: array<string, string>,
   *   queryParameters?: array<string, mixed>,
   *   bodyProperties?: array<string, mixed>,
   * } $options
   * @return ?\Celitech\ESim\Types\GetEsimDeviceResponse
   * @throws \Celitech\Exceptions\CelitechException
   * @throws \Celitech\Exceptions\CelitechApiException
   */
  public function getEsimDevice(
    string $iccid,
    ?array $options = null
  ): ?\Celitech\ESim\Types\GetEsimDeviceResponse {
    Validator::validateString($iccid, 'iccid', minLength: 18, maxLength: 22);

    $resolvedConfig = $this->getResolvedConfig($this->getEsimDeviceConfig, $options);
    $response = $this->sendRequest(
      'get',
      '/esim/' . \Celitech\Core\Client\RawClient::encodePathParam($iccid) . '/device',
      ['scopes' => []],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    try {
      $result = Serializer::deserialize($data, \Celitech\ESim\Types\GetEsimDeviceResponse::class);
    } catch (\JsonException | \TypeError | \InvalidArgumentException $e) {
      throw new \Celitech\Exceptions\CelitechException(
        message: "Failed to deserialize the response: {$e->getMessage()}",
        previous: $e
      );
    }
    return $result;
  }

  /**
   * Get eSIM History
   *
   * @param string $iccid ID of the eSIM
   * @param ?array{
   *   baseUrl?: string,
   *   maxRetries?: int,
   *   timeout?: float,
   *   headers?: array<string, string>,
   *   queryParameters?: array<string, mixed>,
   *   bodyProperties?: array<string, mixed>,
   * } $options
   * @return ?\Celitech\ESim\Types\GetEsimHistoryResponse
   * @throws \Celitech\Exceptions\CelitechException
   * @throws \Celitech\Exceptions\CelitechApiException
   */
  public function getEsimHistory(
    string $iccid,
    ?array $options = null
  ): ?\Celitech\ESim\Types\GetEsimHistoryResponse {
    Validator::validateString($iccid, 'iccid', minLength: 18, maxLength: 22);

    $resolvedConfig = $this->getResolvedConfig($this->getEsimHistoryConfig, $options);
    $response = $this->sendRequest(
      'get',
      '/esim/' . \Celitech\Core\Client\RawClient::encodePathParam($iccid) . '/history',
      ['scopes' => []],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    try {
      $result = Serializer::deserialize($data, \Celitech\ESim\Types\GetEsimHistoryResponse::class);
    } catch (\JsonException | \TypeError | \InvalidArgumentException $e) {
      throw new \Celitech\Exceptions\CelitechException(
        message: "Failed to deserialize the response: {$e->getMessage()}",
        previous: $e
      );
    }
    return $result;
  }

  /**
   * Suspends an eSIM to prevent abuse. All of the eSIM's packages are terminated and its connectivity is switched off. The eSIM is not refunded: the charge stays.
   *
   * @param string $iccid ID of the eSIM
   * @param \Celitech\ESim\Requests\SuspendEsimRequest $request Request parameters
   * @param ?array{
   *   baseUrl?: string,
   *   maxRetries?: int,
   *   timeout?: float,
   *   headers?: array<string, string>,
   *   queryParameters?: array<string, mixed>,
   *   bodyProperties?: array<string, mixed>,
   * } $options
   * @return ?\Celitech\ESim\Types\SuspendEsimResponse
   * @throws \Celitech\Exceptions\CelitechException
   * @throws \Celitech\Exceptions\CelitechApiException
   */
  public function suspendEsim(
    string $iccid,
    \Celitech\ESim\Requests\SuspendEsimRequest $request = new \Celitech\ESim\Requests\SuspendEsimRequest(),
    ?array $options = null
  ): ?\Celitech\ESim\Types\SuspendEsimResponse {
    $idempotencyKey = $request->idempotencyKey;

    Validator::validateString($iccid, 'iccid', minLength: 18, maxLength: 22);
    Validator::validateString($idempotencyKey, 'Idempotency-Key', minLength: 1, maxLength: 255);

    $resolvedConfig = $this->getResolvedConfig($this->suspendEsimConfig, $options);
    $response = $this->sendRequest(
      'post',
      '/esim/' . \Celitech\Core\Client\RawClient::encodePathParam($iccid) . '/suspend',
      [
        'headers' => [
          'Idempotency-Key' => $idempotencyKey
        ],
        'scopes' => []
      ],
      $resolvedConfig
    );
    $data = $response->getBody()->getContents();

    try {
      $result = Serializer::deserialize($data, \Celitech\ESim\Types\SuspendEsimResponse::class);
    } catch (\JsonException | \TypeError | \InvalidArgumentException $e) {
      throw new \Celitech\Exceptions\CelitechException(
        message: "Failed to deserialize the response: {$e->getMessage()}",
        previous: $e
      );
    }
    return $result;
  }
}
