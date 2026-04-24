<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class GetEsimOkResponse implements \JsonSerializable
{
  #[SerializedName('esim')]
  public GetEsimOkResponseEsim $esim;

  public function __construct(GetEsimOkResponseEsim $esim)
  {
    $this->esim = $esim;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    return new self(
      esim: isset($data['esim']) && is_array($data['esim'])
        ? GetEsimOkResponseEsim::fromArray($data['esim'])
        : null
    );
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [
      'esim' => $this->esim
    ];

    return $result;
  }
}
