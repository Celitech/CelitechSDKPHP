<?php

declare(strict_types=1);

namespace Celitech\ESim\Requests;

use Celitech\Core\Json\JsonSerializableType;

class GetEsimRequest extends JsonSerializableType
{
  /**
   * @var string $iccid ID of the eSIM
   */
  public string $iccid;

  /**
   * @param array{
   *   iccid: string,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->iccid = $values['iccid'];
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    return new self([
      'iccid' => $data['iccid']
    ]);
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [];
    $result['iccid'] = $this->iccid;
    return $result;
  }
}
