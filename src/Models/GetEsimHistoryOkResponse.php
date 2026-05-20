<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class GetEsimHistoryOkResponse implements \JsonSerializable
{
  #[SerializedName('esim')]
  public GetEsimHistoryOkResponseEsim $esim;

  public function __construct(GetEsimHistoryOkResponseEsim $esim)
  {
    $this->esim = $esim;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    $instance = new self(
      esim: isset($data['esim']) && is_array($data['esim'])
        ? GetEsimHistoryOkResponseEsim::fromArray($data['esim'])
        : null
    );

    return $instance;
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [];
    $result['esim'] = $this->esim;
    return $result;
  }

  public function validate(): void
  {
    $this->esim->validate();
  }
}
