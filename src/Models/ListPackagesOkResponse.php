<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class ListPackagesOkResponse implements \JsonSerializable
{
  /**
   * @var Packages[]
   */
  #[SerializedName('packages')]
  public array $packages;

  /**
   * The cursor value representing the end of the current page of results. Use this cursor value as the "afterCursor" parameter in your next request to retrieve the subsequent page of results. It ensures that you continue fetching data from where you left off, facilitating smooth pagination
   */
  #[SerializedName('afterCursor')]
  public ?string $afterCursor;

  public function __construct(array $packages, ?string $afterCursor = null)
  {
    $this->packages = $packages;
    $this->afterCursor = $afterCursor;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    $instance = new self(
      packages: array_map(
        fn($item) => is_array($item) ? Packages::fromArray($item) : $item,
        $data['packages']
      ),
      afterCursor: $data['afterCursor']
    );

    return $instance;
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [];
    $result['packages'] = $this->packages;
    $result['afterCursor'] = $this->afterCursor;
    return $result;
  }

  public function validate(): void
  {
    foreach ($this->packages as $item) {
      $item->validate();
    }
  }
}
