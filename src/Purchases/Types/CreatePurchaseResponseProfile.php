<?php

declare(strict_types=1);

namespace Celitech\Purchases\Types;

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
class CreatePurchaseResponseProfile extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * ID of the eSIM
   */
  #[JsonProperty('iccid', required: true)]
  public string $iccid;

  /**
   * QR Code of the eSIM as base64
   */
  #[JsonProperty('activationCode', required: true)]
  public string $activationCode;

  /**
   * Manual Activation Code of the eSIM
   */
  #[JsonProperty('manualActivationCode', required: true)]
  public string $manualActivationCode;

  /**
   * @param array{
   *   iccid: string,
   *   activationCode: string,
   *   manualActivationCode: string,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->iccid = $values['iccid'];
    $this->activationCode = $values['activationCode'];
    $this->manualActivationCode = $values['manualActivationCode'];

    $this->_dirtyFields = [
      'iccid' => true,
      'activationCode' => true,
      'manualActivationCode' => true
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
      'name' => 'activationCode',
      'contents' => $this->activationCode
    ];

    $parts[] = [
      'name' => 'manualActivationCode',
      'contents' => $this->manualActivationCode
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
