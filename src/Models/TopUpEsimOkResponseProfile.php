<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Celitech\Utils\Validator;

class TopUpEsimOkResponseProfile implements \JsonSerializable
{
  /**
   * ID of the eSIM
   */
  #[SerializedName('iccid')]
  public string $iccid;

  public function __construct(string $iccid)
  {
    $this->iccid = $iccid;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    $instance = new self(iccid: $data['iccid']);

    return $instance;
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

  public function validate(): void
  {
    Validator::validateString($this->iccid, 'iccid', minLength: 18, maxLength: 22);
  }
}
