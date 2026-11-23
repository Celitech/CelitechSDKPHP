<?php

declare(strict_types=1);

namespace Celitech\ESim\Types;

use Celitech\Core\Json\JsonSerializableType;
use Celitech\Core\Json\ReflectiveSerialization;
use Celitech\Core\Json\JsonProperty;
use Celitech\Utils\Validator;

/**
 * The reflective deserializer reconstructs this model with `new static($values)`, so a subclass has
 * to keep a constructor it can call with one associative array.
 *
 * @phpstan-consistent-constructor
 */
class GetEsimResponseEsim extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * ID of the eSIM
   */
  #[JsonProperty('iccid', required: true)]
  public string $iccid;

  /**
   * SM-DP+ Address
   */
  #[JsonProperty('smdpAddress', required: true)]
  public string $smdpAddress;

  /**
   * QR Code of the eSIM as base64
   */
  #[JsonProperty('activationCode', required: true)]
  public string $activationCode;

  /**
   * The manual activation code
   */
  #[JsonProperty('manualActivationCode', required: true)]
  public string $manualActivationCode;

  /**
   * Status of the eSIM, possible values are 'RELEASED', 'DOWNLOADED', 'INSTALLED', 'ENABLED', 'DELETED', or 'ERROR'
   */
  #[JsonProperty('status', required: true)]
  public string $status;

  /**
   * Status of the eSIM connectivity, possible values are 'ACTIVE' or 'NOT_ACTIVE'
   */
  #[JsonProperty('connectivityStatus', required: true)]
  public string $connectivityStatus;

  /**
   * Indicates whether the eSIM is currently eligible for a top-up. This flag should be checked before attempting a top-up request.
   */
  #[JsonProperty('isTopUpAllowed', required: true)]
  public bool $isTopUpAllowed;

  /**
   * @param array{
   *   iccid: string,
   *   smdpAddress: string,
   *   activationCode: string,
   *   manualActivationCode: string,
   *   status: string,
   *   connectivityStatus: string,
   *   isTopUpAllowed: bool,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->iccid = $values['iccid'];
    $this->smdpAddress = $values['smdpAddress'];
    $this->activationCode = $values['activationCode'];
    $this->manualActivationCode = $values['manualActivationCode'];
    $this->status = $values['status'];
    $this->connectivityStatus = $values['connectivityStatus'];
    $this->isTopUpAllowed = $values['isTopUpAllowed'];

    $this->_dirtyFields = [
      'iccid' => true,
      'smdpAddress' => true,
      'activationCode' => true,
      'manualActivationCode' => true,
      'status' => true,
      'connectivityStatus' => true,
      'isTopUpAllowed' => true
    ];
  }

  public function toMultipart(): array
  {
    $parts = [];
    $parts[] = [
      'name' => 'iccid',
      'contents' => $this->iccid
    ];

    $parts[] = [
      'name' => 'smdpAddress',
      'contents' => $this->smdpAddress
    ];

    $parts[] = [
      'name' => 'activationCode',
      'contents' => $this->activationCode
    ];

    $parts[] = [
      'name' => 'manualActivationCode',
      'contents' => $this->manualActivationCode
    ];

    $parts[] = [
      'name' => 'status',
      'contents' => $this->status
    ];

    $parts[] = [
      'name' => 'connectivityStatus',
      'contents' => $this->connectivityStatus
    ];

    $parts[] = [
      'name' => 'isTopUpAllowed',
      'contents' => $this->isTopUpAllowed ? 'true' : 'false'
    ];

    return $parts;
  }

  public function validate(): void
  {
    Validator::validateString($this->iccid, 'iccid', minLength: 18, maxLength: 22);
    Validator::validateString(
      $this->activationCode,
      'activationCode',
      minLength: 1000,
      maxLength: 8000
    );
  }
}
