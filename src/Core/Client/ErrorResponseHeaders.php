<?php

declare(strict_types=1);

namespace Celitech\Core\Client;

use Throwable;
use WeakMap;

/**
 * The response headers of an error response, kept beside the exception thrown for it.
 *
 * They are not a constructor argument because a Fern-generated API exception has none for them,
 * and a customer who migrated from Fern may keep that class in place of the generated one. Holding
 * them here lets the SDK throw that class unchanged and still hand the headers to whoever needs
 * them, whichever class was thrown.
 */
final class ErrorResponseHeaders
{
  /** @var ?WeakMap<Throwable, array<string, string[]>> */
  private static ?WeakMap $headers = null;

  /**
   * @template T of Throwable
   * @param T $exception
   * @param array<string, string[]> $headers
   * @return T
   */
  public static function attach(Throwable $exception, array $headers): Throwable
  {
    self::$headers ??= new WeakMap();
    self::$headers[$exception] = $headers;
    return $exception;
  }

  /**
   * @return array<string, string[]> The headers attached to $exception, or an empty array
   */
  public static function of(Throwable $exception): array
  {
    if (self::$headers === null || !self::$headers->offsetExists($exception)) {
      return [];
    }
    return self::$headers[$exception];
  }
}
