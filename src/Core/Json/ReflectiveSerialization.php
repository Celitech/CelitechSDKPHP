<?php

declare(strict_types=1);

namespace Celitech\Core\Json;

use DateTime;
use JsonException;
use Celitech\Core\Types\ArrayType;
use Celitech\Core\Types\Date;
use Celitech\Core\Types\Union;
use ReflectionClass;
use ReflectionNamedType;

/**
 * Attribute-driven JSON serialization for generated models.
 *
 * A model declares its wire shape with `#[JsonProperty]`, `#[ArrayType]`, `#[Date]` and `#[Union]`
 * attributes; this trait reads them once per class and drives both directions from that metadata,
 * so the per-model `fromArray()`/`jsonSerialize()` bodies do not have to be generated.
 *
 * Properties without a `#[JsonProperty]` attribute are ignored, which keeps this trait's own
 * `$_dirtyFields` and `$_undeclaredFields` off the wire.
 */
trait ReflectiveSerialization
{
  /** @var array<string, true> Wire keys that were explicitly assigned. */
  private array $_dirtyFields = [];

  /** @var array<string, mixed> Wire keys fromArray() received that map to no declared field. */
  private array $_undeclaredFields = [];

  /** @var array<string, list<array<string, mixed>>> */
  private static array $__propertyMeta = [];

  /**
   * Mark one or more optional fields as explicitly set so they are included in
   * {@see jsonSerialize()} output even when they hold null.
   *
   *     $pet = new Pet(['name' => 'Buddy']);
   *     $pet->setFields('tag'); // tag (null) will now appear in JSON
   *
   * @param string ...$fields Wire field names to mark as set.
   */
  public function setFields(string ...$fields): static
  {
    foreach ($fields as $field) {
      $this->_dirtyFields[$field] = true;
    }
    return $this;
  }

  /**
   * @return array<string, mixed>
   * @throws JsonException If a value cannot be serialized as its declared type.
   */
  public function jsonSerialize(): array
  {
    $result = [];
    foreach (self::__propertyMeta() as $meta) {
      // Reading a typed property that was never assigned is an Error, not null. Treating it
      // as absent keeps an incompletely constructed model serializable instead of fatal.
      if (!$meta['prop']->isInitialized($this)) {
        continue;
      }
      $value = $meta['prop']->getValue($this);
      if (!$this->_isSet($meta['key'], $value)) {
        continue;
      }
      $result[$meta['key']] = self::__encode($value, $meta);
    }

    // Only a model that opted into retention writes unknown fields back. The rest still report
    // them through getAdditionalProperties(), as Fern does, without resending them.
    $retained =
      property_exists($this, 'additionalProperties') && is_array($this->additionalProperties)
        ? $this->additionalProperties
        : [];

    return array_merge($result, $retained);
  }

  /**
   * Properties present on the wire that did not map to a declared field. A model that retains
   * them exposes its mutable `$additionalProperties`; any other reports what fromArray() received.
   *
   * @return array<string, mixed>
   */
  public function getAdditionalProperties(): array
  {
    if (property_exists($this, 'additionalProperties') && is_array($this->additionalProperties)) {
      /** @var array<string, mixed> */
      return $this->additionalProperties;
    }

    return $this->_undeclaredFields;
  }

  /**
   * @param array<string, mixed> $data
   * @throws JsonException If a required field is absent or a value cannot be deserialized.
   * @throws \TypeError If a present value cannot be assigned to its property, as Fern raises it.
   */
  public static function fromArray(array $data): static
  {
    $values = [];
    $present = [];
    $known = [];
    foreach (self::__propertyMeta() as $meta) {
      $known[$meta['key']] = true;
      if (array_key_exists($meta['key'], $data)) {
        $values[$meta['name']] = self::__decode($data[$meta['key']], $meta);
        $present[$meta['name']] = true;
        continue;
      }
      if ($meta['required']) {
        throw new JsonException(
          'Cannot deserialize ' . static::class . ": missing required field '{$meta['key']}'"
        );
      }
      if ($meta['prop']->getType()?->allowsNull() !== false) {
        // Fern fills every absent nullable field with null before constructing, so a
        // constructor that reads the key directly sees it rather than an undefined key.
        $values[$meta['name']] = null;
      }
    }

    self::__validateValues($values);
    // Every generated constructor takes the one `array $values` this passes, as Fern's base
    // class relies on for its own `new static($args)`, which it ignores the same way.
    // @phpstan-ignore-next-line
    $instance = new static($values);

    // A consumer subclass can add a #[JsonProperty] without widening the constructor it
    // inherits, which assigns only the keys it declares — so the field would be decoded,
    // dropped, and still reported as set. Assigning every wire-present value covers that
    // property; for one the constructor already took from this same array it writes back an
    // identical value, since a fernMode constructor only ever assigns `$values[...]` through.
    foreach (self::__propertyMeta() as $meta) {
      if (isset($present[$meta['name']])) {
        $meta['prop']->setValue($instance, $values[$meta['name']]);
      }
    }

    $instance->_undeclaredFields = array_diff_key($data, $known);
    if (
      property_exists($instance, 'additionalProperties') &&
      is_array($instance->additionalProperties)
    ) {
      $instance->additionalProperties = array_merge(
        $instance->additionalProperties,
        $instance->_undeclaredFields
      );
    }

    $instance->_dirtyFields = [];
    foreach ($known as $key => $unused) {
      if (array_key_exists($key, $data)) {
        $instance->_dirtyFields[$key] = true;
      }
    }

    return $instance;
  }

  /**
   * A field reaches the wire when it holds a value, or when it was explicitly assigned — including
   * explicitly assigned null, which is how a caller expresses "clear this field" rather than
   * "leave it alone". Shared with toMultipart() so both encodings agree on what "set" means.
   */
  protected function _isSet(string $wireKey, mixed $value): bool
  {
    return $value !== null || isset($this->_dirtyFields[$wireKey]);
  }

  /**
   * A mistyped value surfaces as the TypeError Fern's deserializer lets through, not a
   * JsonException: the payload was valid JSON, it just does not fit the model.
   *
   * @param array<string, mixed> $values
   */
  private static function __validateValues(array $values): void
  {
    $probe = (new ReflectionClass(static::class))->newInstanceWithoutConstructor();
    foreach (self::__propertyMeta() as $meta) {
      if (array_key_exists($meta['name'], $values)) {
        $value = $values[$meta['name']];
        $meta['prop']->setValue($probe, $value);
        $assigned = $meta['prop']->getValue($probe);
        // Reflection coerces scalars (`123` into a string property); the constructor runs
        // under strict_types and would throw instead. int to float is allowed by both.
        if ($assigned !== $value && !(is_int($value) && is_float($assigned))) {
          throw new \TypeError(
            'Cannot assign ' .
              get_debug_type($value) .
              ' to property ' .
              static::class .
              '::$' .
              $meta['name'] .
              ' of type ' .
              $meta['prop']->getType()
          );
        }
      }
    }
  }

  /**
   * @param array<string, mixed> $meta
   * @throws JsonException
   */
  private static function __encode(mixed $value, array $meta): mixed
  {
    if ($value === null) {
      return null;
    }
    if ($meta['date'] !== null && $value instanceof DateTime) {
      return $meta['date'] === Date::TYPE_DATE
        ? JsonSerializer::serializeDate($value)
        : JsonSerializer::serializeDateTime($value);
    }
    if ($meta['union'] !== null) {
      return JsonSerializer::serializeUnion($value, $meta['union']);
    }
    if ($meta['array'] !== null && is_array($value)) {
      return JsonSerializer::serializeArray($value, $meta['array']);
    }
    if (is_object($value) && !($value instanceof \stdClass)) {
      return JsonSerializer::serializeObject($value);
    }

    return $value;
  }

  /**
   * @param array<string, mixed> $meta
   * @throws JsonException
   */
  private static function __decode(mixed $value, array $meta): mixed
  {
    if ($meta['date'] !== null && is_string($value)) {
      return $meta['date'] === Date::TYPE_DATE
        ? JsonDeserializer::deserializeDate($value)
        : JsonDeserializer::deserializeDateTime($value);
    }
    if ($meta['array'] !== null && is_array($value)) {
      return JsonDeserializer::deserializeArray($value, $meta['array']);
    }
    if ($meta['union'] !== null) {
      return JsonDeserializer::deserializeUnion($value, $meta['union']);
    }
    if ($meta['class'] !== null && is_array($value)) {
      /** @var array<string, mixed> $value */
      return JsonDeserializer::deserializeObject($value, $meta['class']);
    }
    // A number for a `string` property is kept as its text, as Fern's PHP reads it: a numeric
    // literal (`const: 42`) is typed `string`, so its own value would otherwise be rejected.
    // Lossless, unlike the reverse (a numeric string into an int), which stays an error.
    $type = $meta['prop']->getType();
    if (
      (is_int($value) || is_float($value)) &&
      $type instanceof \ReflectionNamedType &&
      $type->getName() === 'string'
    ) {
      return (string) $value;
    }
    // JSON does not distinguish `3` from `3.0` or `1e3`, and many producers emit the latter for
    // a whole number; Fern reads them into an int. A fractional value is refused here, before the
    // reflection probe would emit an "Implicit conversion ... loses precision" deprecation.
    if (is_float($value) && $type instanceof \ReflectionNamedType && $type->getName() === 'int') {
      // 2^63 is written as a float literal: PHP_INT_MAX rounds up to it as a float, so
      // `<= PHP_INT_MAX` would admit 2^63 and the cast would wrap to PHP_INT_MIN.
      if (
        is_finite($value) &&
        floor($value) === $value &&
        $value >= -9223372036854775808.0 &&
        $value < 9223372036854775808.0
      ) {
        return (int) $value;
      }
      throw new \TypeError(
        'Cannot assign float ' .
          var_export($value, true) .
          ' to property ' .
          static::class .
          '::$' .
          $meta['name'] .
          ' of type ' .
          $type
      );
    }

    return $value;
  }

  /**
   * Keyed by class even though a trait gives each composing class its own static: a consumer
   * subclass of a generated model does not re-`use` the trait, so it shares the parent's copy.
   * Without the key it would inherit the parent's property list and silently ignore its own.
   *
   * @return list<array<string, mixed>>
   */
  private static function __propertyMeta(): array
  {
    $class = static::class;
    if (isset(self::$__propertyMeta[$class])) {
      return self::$__propertyMeta[$class];
    }

    $meta = [];
    foreach ((new ReflectionClass($class))->getProperties() as $property) {
      $jsonProperty = $property->getAttributes(JsonProperty::class)[0] ?? null;
      if ($jsonProperty === null) {
        continue;
      }
      $jsonPropertyData = $jsonProperty->newInstance();
      $type = $property->getType();
      $date = $property->getAttributes(Date::class)[0] ?? null;
      $array = $property->getAttributes(ArrayType::class)[0] ?? null;
      $union = $property->getAttributes(Union::class)[0] ?? null;
      $meta[] = [
        'prop' => $property,
        'name' => $property->getName(),
        'key' => $jsonPropertyData->name,
        'required' => $jsonPropertyData->required,
        'date' => $date?->newInstance()->type,
        'array' => $array?->newInstance()->type,
        'union' => $union?->newInstance(),
        'class' =>
          $type instanceof ReflectionNamedType && !$type->isBuiltin() ? $type->getName() : null
      ];
    }

    return self::$__propertyMeta[$class] = $meta;
  }
}
