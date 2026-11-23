<?php

declare(strict_types=1);

namespace Celitech\Core\Client;

/**
 * A single Server-Sent Event with its WHATWG metadata fields.
 *
 * Yielded by `SseStream::events()`. Iterate the stream itself with a plain `foreach` when only the
 * deserialized payload is wanted; this type exists for the callers that also need the frame's own
 * `event:`, `id:` and `retry:` lines.
 *
 * @template T
 */
final class SseEvent
{
  /**
   * @param T $data Deserialized payload from the frame's `data:` field(s). Multi-line data is
   *   joined with a single newline before deserialization.
   * @param string $event The frame's `event:` field, or an empty string when it declared none.
   * @param string $id The most recent `id:` field. Per the WHATWG spec this PERSISTS across
   *   frames: a later frame with no explicit `id:` inherits the previous one. Empty string until
   *   the first id is seen.
   * @param ?int $retry Reconnection time in milliseconds from `retry:`, or null when the frame
   *   declared none or it did not parse as an integer.
   */
  public function __construct(
    public readonly mixed $data,
    public readonly string $event = '',
    public readonly string $id = '',
    public readonly ?int $retry = null
  ) {
  }
}
