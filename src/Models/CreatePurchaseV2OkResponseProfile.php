<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Celitech\Utils\Validator;

class CreatePurchaseV2OkResponseProfile implements \JsonSerializable
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

  /**
   * iOS Activation Link of the eSIM
   */
  #[SerializedName('iosActivationLink')]
  public string $iosActivationLink;

  /**
   * Android Activation Link of the eSIM
   */
  #[SerializedName('androidActivationLink')]
  public string $androidActivationLink;

  public function __construct(
    string $iccid,
    string $activationCode,
    string $manualActivationCode,
    string $iosActivationLink,
    string $androidActivationLink
  ) {
    $this->iccid = $iccid;
    $this->activationCode = $activationCode;
    $this->manualActivationCode = $manualActivationCode;
    $this->iosActivationLink = $iosActivationLink;
    $this->androidActivationLink = $androidActivationLink;
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
      manualActivationCode: $data['manualActivationCode'],
      iosActivationLink: $data['iosActivationLink'],
      androidActivationLink: $data['androidActivationLink']
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
    $result['iosActivationLink'] = $this->iosActivationLink;
    $result['androidActivationLink'] = $this->androidActivationLink;
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
      ],

      [
        'name' => 'iosActivationLink',
        'contents' => $this->iosActivationLink
      ],

      [
        'name' => 'androidActivationLink',
        'contents' => $this->androidActivationLink
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
