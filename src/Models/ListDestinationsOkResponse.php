<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class ListDestinationsOkResponse implements \JsonSerializable
{
  /**
   * @var Destinations[]
   */
  #[SerializedName('destinations')]
  public array $destinations;

  public function __construct(array $destinations)
  {
    $this->destinations = $destinations;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    return new self(
      destinations: isset($data['destinations']) && is_array($data['destinations'])
        ? array_map(
          fn($item) => is_array($item) ? Destinations::fromArray($item) : $item,
          $data['destinations']
        )
        : null
    );
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [
      'destinations' => $this->destinations
    ];

    return $result;
  }
}
