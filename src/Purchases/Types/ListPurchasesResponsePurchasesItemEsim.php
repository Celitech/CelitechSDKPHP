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
class ListPurchasesResponsePurchasesItemEsim extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * ID of the eSIM
   */
  #[JsonProperty('iccid', required: true)]
  public string $iccid;

  /**
   * @param array{
   *   iccid: string,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->iccid = $values['iccid'];

    $this->_dirtyFields = [
      'iccid' => true
    ];
  }

  public function toMultipart(): array
  {
    $parts = [];
    $parts[] = [
      'name' => 'iccid',
      'contents' => $this->iccid
    ];

    return $parts;
  }

  public function validate(): void
  {
    Validator::validateString($this->iccid, 'iccid', minLength: 18, maxLength: 22);
  }
}
