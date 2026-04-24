<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class ListPurchasesOkResponse implements \JsonSerializable
{
  /**
   * @var Purchases[]
   */
  #[SerializedName('purchases')]
  public array $purchases;

  /**
   * The cursor value representing the end of the current page of results. Use this cursor value as the "afterCursor" parameter in your next request to retrieve the subsequent page of results. It ensures that you continue fetching data from where you left off, facilitating smooth pagination.
   */
  #[SerializedName('afterCursor')]
  public ?string $afterCursor;

  public function __construct(array $purchases, ?string $afterCursor = null)
  {
    $this->purchases = $purchases;
    $this->afterCursor = $afterCursor;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    return new self(
      purchases: isset($data['purchases']) && is_array($data['purchases'])
        ? array_map(
          fn($item) => is_array($item) ? Purchases::fromArray($item) : $item,
          $data['purchases']
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
      'purchases' => $this->purchases,
      'afterCursor' => $this->afterCursor
    ];

    return $result;
  }
}
