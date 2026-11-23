<?php

declare(strict_types=1);

namespace Celitech\Purchases\Types;

use Celitech\Core\Json\JsonSerializableType;
use Celitech\Core\Json\ReflectiveSerialization;
use Celitech\Core\Json\JsonProperty;
use Celitech\Core\Types\Date;
use DateTime;

/**
 * The reflective deserializer reconstructs this model with `new static($values)`, so a subclass has
 * to keep a constructor it can call with one associative array.
 *
 * @phpstan-consistent-constructor
 */
class CreatePurchaseV2ResponseItemPurchase extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * ID of the purchase
   */
  #[JsonProperty('id', required: true)]
  public string $id;

  /**
   * ID of the package
   */
  #[JsonProperty('packageId', required: true)]
  public string $packageId;

  /**
   * Creation date of the purchase in the format 'yyyy-MM-ddThh:mm:ssZZ'
   */
  #[JsonProperty('createdDate', required: true), Date(Date::TYPE_DATETIME)]
  public DateTime $createdDate;

  /**
   * @param array{
   *   id: string,
   *   packageId: string,
   *   createdDate: DateTime,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->id = $values['id'];
    $this->packageId = $values['packageId'];
    $this->createdDate = $values['createdDate'];

    $this->_dirtyFields = [
      'id' => true,
      'packageId' => true,
      'createdDate' => true
    ];
  }

  public function toMultipart(): array
  {
    $parts = [];
    $parts[] = [
      'name' => 'id',
      'contents' => $this->id
    ];

    $parts[] = [
      'name' => 'packageId',
      'contents' => $this->packageId
    ];

    $parts[] = [
      'name' => 'createdDate',
      'contents' => $this->createdDate
    ];

    return $parts;
  }

  public function validate(): void
  {
  }
}
