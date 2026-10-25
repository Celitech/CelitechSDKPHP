<?php

declare(strict_types=1);

namespace Celitech\Utils;

use Celitech\Exceptions\CelitechException;

/**
 * Lightweight JSON serialization utility.
 *
 * The generated models own their own wire mapping via fromArray()/jsonSerialize(),
 * so no third-party serializer is needed — this uses native json_encode/json_decode
 * plus those model hooks. The public API matches the default Serializer so call
 * sites are unchanged.
 */
class Serializer
{
  /**
   * Deserialize a JSON string into a model, an array of models, or a scalar.
   *
   * @param string $data JSON string to deserialize
   * @param string $class Target class; a trailing `[]` denotes an array of that class
   * @return mixed Populated instance(s), or the decoded value for scalars/unknown classes
   */
  public static function deserialize(string $data, string $class): mixed
  {
    if (trim($data) === '') {
      return null;
    }

    try {
      $decoded = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
    } catch (\JsonException $e) {
      // An empty body is a legitimate response and returns null above. A body that is not
      // JSON is not: decoding it silently to null left the caller unable to tell a server
      // that sent nothing from one that sent HTML against a JSON-typed operation. Fern
      // raises its base exception here with this message, so the two agree.
      throw new CelitechException(
        message: "Failed to deserialize response: {$e->getMessage()}",
        previous: $e
      );
    }

    if (str_ends_with($class, '[]')) {
      $itemClass = substr($class, 0, -2);
      if (!is_array($decoded)) {
        return $decoded;
      }
      return array_map(fn($item) => self::coerce($item, $itemClass), $decoded);
    }

    return self::coerce($decoded, $class);
  }

  /**
   * Coerce a decoded value into an instance of $class when possible.
   *
   * Unknown backed-enum values become null (tryFrom) to preserve
   * forward-compatibility, matching the tolerant behavior of the default
   * serializer.
   *
   * @param mixed $value
   * @param string $class
   * @return mixed
   */
  private static function coerce(mixed $value, string $class): mixed
  {
    if (
      (is_string($value) || is_int($value)) &&
      enum_exists($class) &&
      is_subclass_of($class, \BackedEnum::class)
    ) {
      return $class::tryFrom($value);
    }

    if (class_exists($class) && method_exists($class, 'fromArray')) {
      if (is_array($value)) {
        return $class::fromArray($value);
      }
      if ($value === null) {
        return null;
      }
      // Handing a scalar back for a model class left the caller's `->validate()` to fail as a
      // PHP Error. Fern raises its base exception here, with this message.
      throw new CelitechException(
        message: 'Failed to deserialize response: Unexpected non-array decoded type: ' .
          gettype($value)
      );
    }

    return $value;
  }

  /**
   * Serialize an object (or array/scalar) to its JSON-compatible representation.
   *
   * Objects are round-tripped through native json_encode, which invokes each
   * model's JsonSerializable::jsonSerialize() (dirty-field and additional-property
   * behavior is owned by the model), then decoded back to an associative array.
   *
   * @param mixed $object
   * @return mixed
   */
  public static function serialize(mixed $object): mixed
  {
    // Surface encode failures (invalid UTF-8, NAN/INF, resources) instead of
    // silently sending a null field on the wire.
    $json = json_encode($object);
    if ($json === false) {
      throw new \JsonException('Failed to serialize value: ' . json_last_error_msg());
    }

    return json_decode($json, true);
  }

  /**
   * One parameter component as it goes on the wire. A bool is `true`/`false` rather than PHP's
   * `1`/``, and a structured value is JSON rather than the literal `Array` that string
   * concatenation produces.
   */
  public static function serializeParameterValue(mixed $value): string
  {
    if (is_bool($value)) {
      return $value ? 'true' : 'false';
    }

    if ($value === null) {
      return '';
    }

    if (is_scalar($value) || (is_object($value) && method_exists($value, '__toString'))) {
      return (string) $value;
    }

    // An array is already JSON-shaped; only an object needs normalizing first.
    $encoded = json_encode(is_array($value) ? $value : self::serialize($value));
    if ($encoded === false) {
      throw new \JsonException('Failed to serialize parameter value: ' . json_last_error_msg());
    }

    return $encoded;
  }

  /**
   * Serialize a `date` value (OpenAPI `format: date`) to its wire form.
   */
  public static function serializeDate(\DateTime $date): string
  {
    return $date->format('Y-m-d');
  }

  /**
   * Serialize a `date-time` value (OpenAPI `format: date-time`) to its wire form.
   *
   * RFC 3339 renders UTC as `+00:00`; Fern normalizes that to `Z`, so the two generators put
   * byte-identical timestamps on the wire. A fractional second is kept (trailing zeros trimmed),
   * so a value read from the API is written back unchanged.
   */
  public static function serializeDateTime(\DateTime $date): string
  {
    $fraction = rtrim($date->format('u'), '0');
    $formatted =
      $date->format('Y-m-d\\TH:i:s') .
      ($fraction === '' ? '' : ".{$fraction}") .
      $date->format('P');
    if (str_ends_with($formatted, '+00:00')) {
      return substr($formatted, 0, -6) . 'Z';
    }

    return $formatted;
  }

  /**
   * Parse a `date` wire value. The leading `!` resets the time fields, so a date-only value
   * does not inherit the current clock.
   *
   * @throws \JsonException If the value is not a valid date.
   */
  public static function deserializeDate(string $value): \DateTime
  {
    $date = \DateTime::createFromFormat('!Y-m-d', $value);
    if ($date === false) {
      throw new \JsonException("Failed to create date from string: $value");
    }

    return $date;
  }

  /**
   * Parse a `date-time` wire value.
   *
   * @throws \JsonException If the value is not a valid date-time.
   */
  public static function deserializeDateTime(string $value): \DateTime
  {
    try {
      return new \DateTime($value);
    } catch (\Exception $e) {
      throw new \JsonException("Failed to create DateTime from string: $value", previous: $e);
    }
  }
}
