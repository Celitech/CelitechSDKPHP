<?php

declare(strict_types=1);

namespace Celitech\Core\Json;

use JsonException;

/**
 * Base class for generated models, providing shared JSON serialization entry points.
 *
 * Every model extends it, so `toJson`, `fromJson`, `jsonDeserialize` and `getAdditionalProperties`
 * are always available. Because they are inherited rather than copied into each model, adding them
 * costs one file instead of ~40 lines in every generated model.
 *
 * `jsonSerialize()` is deliberately NOT redeclared here. Models declare it with return types that
 * vary by shape — `array` for objects, `mixed` for oneOf/anyOf unions — and a base declaration of
 * `array` would make the union models' wider `mixed` an invalid covariant override, which is a
 * fatal at class-load time. \JsonSerializable already requires the method, so the contract holds
 * without this class narrowing it.
 */
abstract class JsonSerializableType implements \JsonSerializable
{
  /**
   * Serializes the object to a JSON string.
   *
   * @throws JsonException If encoding fails.
   */
  public function toJson(): string
  {
    $serialized = $this->jsonSerialize();

    // An empty model has to encode as `{}`, not `[]`: PHP cannot tell an empty map from an
    // empty list, and json_encode picks the array form, which a server reading an object
    // would reject.
    $payload = is_array($serialized) && $serialized === [] ? new \stdClass() : $serialized;

    return json_encode($payload, JSON_THROW_ON_ERROR);
  }

  /**
   * Deserializes a JSON string into an instance of the calling class.
   *
   * @throws JsonException If decoding fails or the payload is not a JSON object.
   */
  public static function fromJson(string $json): static
  {
    $decoded = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    if (!is_array($decoded)) {
      throw new JsonException('Unexpected non-array decoded type: ' . gettype($decoded));
    }

    /** @var array<string, mixed> $decoded */
    // static:: rather than self:: so a subclass override is dispatched by late static binding.
    return static::jsonDeserialize($decoded);
  }

  /**
   * Deserializes an array into an instance of the calling class.
   *
   * @param array<string, mixed> $data
   * @throws JsonException If the calling class cannot be constructed from an array.
   */
  public static function jsonDeserialize(array $data): static
  {
    // Every model template emits fromArray(), but it is declared on the subclass rather than
    // here: the oneOf template widens its parameter to `mixed` for enum variants, so an
    // abstract signature on this class could not cover all four shapes. The guard keeps the
    // failure explicit instead of surfacing as an undefined-method fatal.
    if (!method_exists(static::class, 'fromArray')) {
      throw new JsonException(static::class . ' cannot be deserialized: no fromArray().');
    }

    /** @var static */
    return static::fromArray($data);
  }

  /**
   * Properties present on the wire that did not map to a declared field.
   *
   * @return array<string, mixed>
   */
  public function getAdditionalProperties(): array
  {
    // The backing property exists only on models that preserve unknown fields (FSM-251);
    // for the rest there is nothing to collect and an empty map is the honest answer.
    if (!property_exists($this, 'additionalProperties') || !is_array($this->additionalProperties)) {
      return [];
    }

    /** @var array<string, mixed> */
    return $this->additionalProperties;
  }
}
