<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

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
    return new self(
      iccid: $data['iccid'] ?? null,
      smdpAddress: $data['smdpAddress'] ?? null,
      activationCode: $data['activationCode'] ?? null,
      manualActivationCode: $data['manualActivationCode'] ?? null,
      status: $data['status'] ?? null,
      connectivityStatus: $data['connectivityStatus'] ?? null,
      isTopUpAllowed: $data['isTopUpAllowed'] ?? null
    );
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [
      'iccid' => $this->iccid,
      'smdpAddress' => $this->smdpAddress,
      'activationCode' => $this->activationCode,
      'manualActivationCode' => $this->manualActivationCode,
      'status' => $this->status,
      'connectivityStatus' => $this->connectivityStatus,
      'isTopUpAllowed' => $this->isTopUpAllowed
    ];

    return $result;
  }
}
