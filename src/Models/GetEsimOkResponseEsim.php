<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Celitech\Utils\Validator;

class GetEsimOkResponseEsim implements \JsonSerializable
{
  /**
   * ID of the eSIM
   */
  #[SerializedName('iccid')]
  public string $iccid;

  /**
   * SM-DP+ Address
   */
  #[SerializedName('smdpAddress')]
  public string $smdpAddress;

  /**
   * QR Code of the eSIM as base64
   */
  #[SerializedName('activationCode')]
  public string $activationCode;

  /**
   * The manual activation code
   */
  #[SerializedName('manualActivationCode')]
  public string $manualActivationCode;

  /**
   * Status of the eSIM, possible values are 'RELEASED', 'DOWNLOADED', 'INSTALLED', 'ENABLED', 'DELETED', or 'ERROR'
   */
  #[SerializedName('status')]
  public string $status;

  /**
   * Status of the eSIM connectivity, possible values are 'ACTIVE' or 'NOT_ACTIVE'
   */
  #[SerializedName('connectivityStatus')]
  public string $connectivityStatus;

  /**
   * Indicates whether the eSIM is currently eligible for a top-up. This flag should be checked before attempting a top-up request.
   */
  #[SerializedName('isTopUpAllowed')]
  public bool $isTopUpAllowed;

  public function __construct(
    string $iccid,
    string $smdpAddress,
    string $activationCode,
    string $manualActivationCode,
    string $status,
    string $connectivityStatus,
    bool $isTopUpAllowed
  ) {
    $this->iccid = $iccid;
    $this->smdpAddress = $smdpAddress;
    $this->activationCode = $activationCode;
    $this->manualActivationCode = $manualActivationCode;
    $this->status = $status;
    $this->connectivityStatus = $connectivityStatus;
    $this->isTopUpAllowed = $isTopUpAllowed;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    $instance = new self(
      iccid: $data['iccid'],
      smdpAddress: $data['smdpAddress'],
      activationCode: $data['activationCode'],
      manualActivationCode: $data['manualActivationCode'],
      status: $data['status'],
      connectivityStatus: $data['connectivityStatus'],
      isTopUpAllowed: $data['isTopUpAllowed']
    );

    return $instance;
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [];
    $result['iccid'] = $this->iccid;
    $result['smdpAddress'] = $this->smdpAddress;
    $result['activationCode'] = $this->activationCode;
    $result['manualActivationCode'] = $this->manualActivationCode;
    $result['status'] = $this->status;
    $result['connectivityStatus'] = $this->connectivityStatus;
    $result['isTopUpAllowed'] = $this->isTopUpAllowed;
    return $result;
  }

  public function toMultipart(): array
  {
    return [
      [
        'name' => 'iccid',
        'contents' => $this->iccid
      ],

      [
        'name' => 'smdpAddress',
        'contents' => $this->smdpAddress
      ],

      [
        'name' => 'activationCode',
        'contents' => $this->activationCode
      ],

      [
        'name' => 'manualActivationCode',
        'contents' => $this->manualActivationCode
      ],

      [
        'name' => 'status',
        'contents' => $this->status
      ],

      [
        'name' => 'connectivityStatus',
        'contents' => $this->connectivityStatus
      ],

      [
        'name' => 'isTopUpAllowed',
        'contents' => $this->isTopUpAllowed ? 'true' : 'false'
      ]
    ];
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
