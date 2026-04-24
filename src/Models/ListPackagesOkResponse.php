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
    return new self(
      packages: isset($data['packages']) && is_array($data['packages'])
        ? array_map(
          fn($item) => is_array($item) ? Packages::fromArray($item) : $item,
          $data['packages']
        )
        : null,
      afterCursor: $data['afterCursor'] ?? null
    );
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [
      'packages' => $this->packages,
      'afterCursor' => $this->afterCursor
    ];

    return $result;
  }
}
