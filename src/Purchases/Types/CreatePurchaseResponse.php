<?php

declare(strict_types=1);

namespace Celitech\Purchases\Types;

use Celitech\Core\Json\JsonSerializableType;
use Celitech\Core\Json\ReflectiveSerialization;
use Celitech\Core\Json\JsonProperty;

/**
 * The reflective deserializer reconstructs this model with `new static($values)`, so a subclass has
 * to keep a constructor it can call with one associative array.
 *
 * @phpstan-consistent-constructor
 */
class CreatePurchaseResponse extends JsonSerializableType
{
  use ReflectiveSerialization;

  #[JsonProperty('purchase', required: true)]
  public \Celitech\Purchases\Types\CreatePurchaseResponsePurchase $purchase;

  #[JsonProperty('profile', required: true)]
  public \Celitech\Purchases\Types\CreatePurchaseResponseProfile $profile;

  /**
   * @param array{
   *   purchase: \Celitech\Purchases\Types\CreatePurchaseResponsePurchase,
   *   profile: \Celitech\Purchases\Types\CreatePurchaseResponseProfile,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->purchase = $values['purchase'];
    $this->profile = $values['profile'];

    $this->_dirtyFields = [
      'purchase' => true,
      'profile' => true
    ];
  }

  public function toMultipart(): array
  {
    $parts = [];
    $parts[] = [
      'name' => 'purchase',
      'contents' => json_encode($this->purchase),
      'headers' => ['Content-Type' => 'application/json']
    ];

    $parts[] = [
      'name' => 'profile',
      'contents' => json_encode($this->profile),
      'headers' => ['Content-Type' => 'application/json']
    ];

    return $parts;
  }

  public function validate(): void
  {
    $this->purchase->validate();
    $this->profile->validate();
  }
}
