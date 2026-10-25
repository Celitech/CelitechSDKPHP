<?php

declare(strict_types=1);

namespace Celitech\Core\Client;

/**
 * The return value of a generated JSON-lines streaming method, as Fern's `JsonStream`.
 *
 * `foreach ($stream as $chunk)` yields one deserialized chunk per non-empty line, which is what the
 * `\Generator` this replaces yielded. One pass only: the lines come from a live HTTP body.
 *
 * @template T
 * @implements \IteratorAggregate<int, T>
 */
final class JsonStream implements \IteratorAggregate
{
  /**
   * @param \Generator<int, string> $lines
   * @param \Closure(string): T $deserialize
   * @param ?string $terminator A line that ends the stream, or null to read until the body ends.
   */
  public function __construct(
    private readonly \Generator $lines,
    private readonly \Closure $deserialize,
    private readonly ?string $terminator = null
  ) {
  }

  /**
   * @return \Generator<int, T>
   */
  public function getIterator(): \Generator
  {
    foreach ($this->lines as $line) {
      if (trim($line) === '') {
        continue;
      }
      if ($this->terminator !== null && trim($line) === $this->terminator) {
        return;
      }
      // A parse failure propagates: swallowing it would end the stream as if it were empty.
      yield ($this->deserialize)($line);
    }
  }
}
