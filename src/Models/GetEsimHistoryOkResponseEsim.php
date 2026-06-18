<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Celitech\Utils\Validator;

class GetEsimHistoryOkResponseEsim implements \JsonSerializable
{
  /**
   * ID of the eSIM
   */
  #[SerializedName('iccid')]
  public string $iccid;

  /**
   * @var History[]
   */
  #[SerializedName('history')]
  public array $history;

  public function __construct(string $iccid, array $history)
  {
    $this->iccid = $iccid;
    $this->history = $history;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    $instance = new self(
      iccid: $data['iccid'],
      history: array_map(
        fn($item) => is_array($item) ? History::fromArray($item) : $item,
        $data['history']
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
    $result['iccid'] = $this->iccid;
    $result['history'] = $this->history;
    return $result;
  }

  public function toMultipart(): array
  {
    return [
      [
        'name' => 'iccid',
        'contents' => $this->iccid
      ],

      [
        'name' => 'history',
        'contents' => $this->history
      ]
    ];
  }

  public function validate(): void
  {
    Validator::validateString($this->iccid, 'iccid', minLength: 18, maxLength: 22);
    foreach ($this->history as $item) {
      $item->validate();
    }
  }
}
