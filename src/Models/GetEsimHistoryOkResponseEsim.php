<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

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
    return new self(
      iccid: $data['iccid'] ?? null,
      history: isset($data['history']) && is_array($data['history'])
        ? array_map(
          fn($item) => is_array($item) ? History::fromArray($item) : $item,
          $data['history']
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
      'iccid' => $this->iccid,
      'history' => $this->history
    ];

    return $result;
  }
}
