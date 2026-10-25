<?php

namespace Celitech\Core\Json;

use JsonException;

class Utils
{
  /**
   * Determines if the given type represents a map.
   *
   * @param array<mixed> $type The type definition from the annotation.
   * @return bool True if the type is a map, false if it's a list.
   */
  public static function isMapType(array $type): bool
  {
    return count($type) === 1 && !array_is_list($type);
  }

  /**
   * Casts the key to the appropriate type based on the key type.
   *
   * A `float` key type is lossy: PHP arrays cannot hold float keys, so the value is truncated to
   * an int and `1.2` and `1.8` both become `1`. In a map whose keys differ only after the decimal
   * point, the later entry overwrites the earlier one and that entry is lost without an error.
   *
   * @param mixed $key The key to be cast.
   * @param string $keyType The type to cast the key to ('string', 'integer', 'float').
   * @return int|string The casted key.
   * @throws JsonException
   */
  public static function castKey(mixed $key, string $keyType): int|string
  {
    if (!is_scalar($key)) {
      throw new JsonException('Key must be a scalar type.');
    }
    return match ($keyType) {
      'integer' => (int) $key,
      // PHP arrays don't support float keys; truncate to int
      'float' => (int) $key,
      'string' => (string) $key,
      default => is_int($key) ? $key : (string) $key
    };
  }

  /**
   * Returns a human-readable representation of the input's type.
   *
   * @param mixed $input The input value to determine the type of.
   * @return string A readable description of the input type.
   */
  public static function getReadableType(mixed $input): string
  {
    if (is_object($input)) {
      return get_class($input);
    } elseif (is_array($input)) {
      return 'array(' . count($input) . ' items)';
    } elseif (is_null($input)) {
      return 'null';
    } else {
      return gettype($input);
    }
  }
}
