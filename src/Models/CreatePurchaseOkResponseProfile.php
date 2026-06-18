<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Celitech\Utils\Validator;

class CreatePurchaseOkResponseProfile implements \JsonSerializable
{
  /**
   * ID of the eSIM
   */
  #[SerializedName('iccid')]
  public string $iccid;

  /**
   * QR Code of the eSIM as base64
   */
  #[SerializedName('activationCode')]
  public string $activationCode;

  /**
   * Manual Activation Code of the eSIM
   */
  #[SerializedName('manualActivationCode')]
  public string $manualActivationCode;

  public function __construct(string $iccid, string $activationCode, string $manualActivationCode)
  {
    $this->iccid = $iccid;
    $this->activationCode = $activationCode;
    $this->manualActivationCode = $manualActivationCode;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    $instance = new self(
      iccid: $data['iccid'],
      activationCode: $data['activationCode'],
      manualActivationCode: $data['manualActivationCode']
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
    $result['activationCode'] = $this->activationCode;
    $result['manualActivationCode'] = $this->manualActivationCode;
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
        'name' => 'activationCode',
        'contents' => $this->activationCode
      ],

      [
        'name' => 'manualActivationCode',
        'contents' => $this->manualActivationCode
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
