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
    $instance = new self(
      destinations: array_map(
        fn($item) => is_array($item) ? Destinations::fromArray($item) : $item,
        $data['destinations']
      )
    );

    return $instance;
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [];
    $result['destinations'] = $this->destinations;
    return $result;
  }

  public function toMultipart(): array
  {
    return [
      [
        'name' => 'destinations',
        'contents' => json_encode($this->destinations)
      ]
    ];
  }

  public function validate(): void
  {
    foreach ($this->destinations as $item) {
      $item->validate();
    }
  }
}
