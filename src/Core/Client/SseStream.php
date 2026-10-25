<?php

declare(strict_types=1);

namespace Celitech\Core\Client;

/**
 * The return value of a generated `text/event-stream` method.
 *
 * Implements `IteratorAggregate` so `foreach ($stream as $payload)` yields deserialized payloads —
 * the shape every caller had before this type existed, which is why introducing it is not a
 * breaking change. `events()` is the second accessor, for callers that also need each frame's
 * `event:`, `id:` and `retry:`.
 *
 * ONE PASS ONLY. The underlying frames arrive from a `Generator` over a live HTTP body, so the two
 * accessors consume the same sequence: whichever is iterated first drains it, and the other yields
 * nothing. Pick one per response.
 *
 * @template T
 * @implements \IteratorAggregate<int, T>
 */
final class SseStream implements \IteratorAggregate
{
  /**
   * @param \Generator<int, array{data: string, event: string, id: string, retry: ?int}> $frames
   * @param \Closure(string, array{data: string, event: string, id: string, retry: ?int}): T $deserialize
   *   The frame is always passed, even though most deserializers declare only the first
   *   parameter — PHP does not error on an extra argument a closure never reads. A
   *   protocol-discriminated union's deserializer is the one that reads it, to inject the frame's
   *   `event:` name into the payload before deserializing.
   * @param ?string $terminator The terminal `data` value this operation declared, or null for
   *                            none — in which case iteration ends when the server closes the
   *                            stream rather than on any sentinel.
   */
  public function __construct(
    private readonly \Generator $frames,
    private readonly \Closure $deserialize,
    private readonly ?string $terminator = null
  ) {
  }

  /**
   * @return \Generator<int, T>
   */
  public function getIterator(): \Generator
  {
    foreach ($this->payloads() as [$payload]) {
      yield $payload;
    }
  }

  /**
   * @return \Generator<int, SseEvent<T>>
   */
  public function events(): \Generator
  {
    foreach ($this->payloads() as [$payload, $frame]) {
      yield new SseEvent($payload, $frame['event'], $frame['id'], $frame['retry']);
    }
  }

  /**
   * Shared by both accessors so the framing rules — skip blanks, stop at the sentinel, propagate
   * a parse failure — cannot drift between "iterate the payloads" and "iterate the events".
   *
   * @return \Generator<int, array{0: T, 1: array{data: string, event: string, id: string, retry: ?int}}>
   */
  private function payloads(): \Generator
  {
    foreach ($this->frames as $frame) {
      $data = trim($frame['data']);
      if ($data === '') {
        continue;
      }
      // Matched BEFORE deserialization: the sentinel is not valid JSON for any model, so it
      // would otherwise surface as a parse error rather than a clean end. The value is the
      // operation's, not the runtime's — `[DONE]` is an LLM convention and applying it to a
      // stream that did not declare it ends a long-lived one the moment a payload matches.
      if ($this->terminator !== null && $data === $this->terminator) {
        return;
      }

      yield [($this->deserialize)($frame['data'], $frame), $frame];
    }
  }
}
