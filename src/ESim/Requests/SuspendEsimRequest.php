<?php

declare(strict_types=1);

namespace Celitech\ESim\Requests;

use Celitech\Core\Json\JsonSerializableType;

class SuspendEsimRequest extends JsonSerializableType
{
  /**
   * @var ?string $idempotencyKey Optional. A unique value (for example a UUID) that makes this request safe to retry. If a request with the same `Idempotency-Key` and the same body succeeds, repeating it returns the original response instead of performing the action again, and the response includes the header `Idempotent-Replayed: true`. Keys are scoped to your credentials and remembered for 3 days. Only successful (2xx) responses are remembered, so a request that failed can be retried with the same key. Reusing a key with a different request returns `422`; sending it while the original request is still being processed returns `409`. If the idempotency store is temporarily unavailable, the request is not processed and returns `503`; retry it. Must be 1 to 255 printable ASCII characters, without spaces.
   */
  public ?string $idempotencyKey;

  /**
   * @param array{
   *   idempotencyKey?: ?string,
   * } $values
   */
  public function __construct(array $values = [])
  {
    $this->idempotencyKey = $values['idempotencyKey'] ?? null;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    return new self([
      'idempotencyKey' => $data['Idempotency-Key'] ?? null
    ]);
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [];
    if ($this->idempotencyKey !== null) {
      $result['Idempotency-Key'] = $this->idempotencyKey;
    }
    return $result;
  }
}
