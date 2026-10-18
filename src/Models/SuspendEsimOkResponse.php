<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class SuspendEsimOkResponse implements \JsonSerializable
{
  /**
   * Confirmation message
   */
  #[SerializedName('message')]
  public string $message;

  public function __construct(string $message)
  {
    $this->message = $message;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    $instance = new self(message: $data['message']);

    return $instance;
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [];
    $result['message'] = $this->message;
    return $result;
  }

  public function toMultipart(): array
  {
    $parts = [];
    $parts[] = [
      'name' => 'message',
      'contents' => $this->message
    ];

    return $parts;
  }

  public function validate(): void
  {
  }
}
