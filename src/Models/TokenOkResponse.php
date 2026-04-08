<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class TokenOkResponse implements \JsonSerializable
{
  /**
   * The generated token
   */
  #[SerializedName('token')]
  public string $token;

  public function __construct(string $token)
  {
    $this->token = $token;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    return new self(token: $data['token'] ?? null);
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [
      'token' => $this->token
    ];

    return $result;
  }
}
