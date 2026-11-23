<?php

declare(strict_types=1);

namespace Celitech\Packages\Types;

use Celitech\Core\Json\JsonSerializableType;
use Celitech\Core\Json\ReflectiveSerialization;
use Celitech\Core\Json\JsonProperty;
use Celitech\Core\Types\ArrayType;

/**
 * The reflective deserializer reconstructs this model with `new static($values)`, so a subclass has
 * to keep a constructor it can call with one associative array.
 *
 * @phpstan-consistent-constructor
 */
class ListPackagesResponse extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * @var \Celitech\Packages\Types\ListPackagesResponsePackagesItem[]
   */
  #[
    JsonProperty('packages', required: true),
    ArrayType([\Celitech\Packages\Types\ListPackagesResponsePackagesItem::class])
  ]
  public array $packages;

  /**
   * The cursor value representing the end of the current page of results. Use this cursor value as the "afterCursor" parameter in your next request to retrieve the subsequent page of results. It ensures that you continue fetching data from where you left off, facilitating smooth pagination
   */
  #[JsonProperty('afterCursor', required: true)]
  public ?string $afterCursor;

  /**
   * @param array{
   *   packages: \Celitech\Packages\Types\ListPackagesResponsePackagesItem[],
   *   afterCursor: ?string,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->packages = $values['packages'];
    $this->afterCursor = $values['afterCursor'];

    $this->_dirtyFields = [
      'packages' => true,
      'afterCursor' => true
    ];
  }

  public function toMultipart(): array
  {
    $parts = [];
    $parts[] = [
      'name' => 'packages',
      'contents' => json_encode($this->packages),
      'headers' => ['Content-Type' => 'application/json']
    ];

    $parts[] = [
      'name' => 'afterCursor',
      'contents' => $this->afterCursor
    ];

    return $parts;
  }

  public function validate(): void
  {
    foreach ($this->packages as $item) {
      $item->validate();
    }
  }
}
