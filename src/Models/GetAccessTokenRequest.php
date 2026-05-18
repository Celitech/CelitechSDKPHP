<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class GetAccessTokenRequest implements \JsonSerializable
{
  #[SerializedName('grant_type')]
  public ?GrantType $grantType;

  #[SerializedName('client_id')]
  public ?string $clientId;

  #[SerializedName('client_secret')]
  public ?string $clientSecret;

  /** @var array<string, true> Tracks which fields were explicitly set */
  private array $_dirtyFields = [];

  public function __construct(
    ?GrantType $grantType = null,
    ?string $clientId = null,
    ?string $clientSecret = null
  ) {
    $this->grantType = $grantType;
    $this->clientId = $clientId;
    $this->clientSecret = $clientSecret;
  }

  /**
   * Mark one or more optional fields as explicitly set so they are
   * included in {@see jsonSerialize()} output.
   *
   * Constructor-created objects automatically track required fields and
   * any optional field passed with a non-default value. Use this method
   * to force-include a field that was left at its default (e.g. explicit null):
   *
   *     $pet = new Pet(name: 'Buddy');
   *     $pet->setFields('tag'); // tag (null) will now appear in JSON
   *
   * Objects created via {@see fromArray()} already track every field
   * present in the input data, so setFields() is not needed for them.
   *
   * @param string ...$fields JSON field names (original API names) to mark as set
   * @return static
   */
  public function setFields(string ...$fields): static
  {
    foreach ($fields as $field) {
      $this->_dirtyFields[$field] = true;
    }
    return $this;
  }

  /**
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    $instance = new self(
      grantType: isset($data['grant_type']) &&
      (is_string($data['grant_type']) || is_int($data['grant_type']))
        ? GrantType::tryFrom($data['grant_type'])
        : null,
      clientId: $data['client_id'] ?? null,
      clientSecret: $data['client_secret'] ?? null
    );
    $instance->_dirtyFields = [];
    foreach (['grant_type', 'client_id', 'client_secret'] as $field) {
      if (array_key_exists($field, $data)) {
        $instance->_dirtyFields[$field] = true;
      }
    }
    return $instance;
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [];
    if (array_key_exists('grant_type', $this->_dirtyFields)) {
      $result['grant_type'] = $this->grantType;
    }
    if (array_key_exists('client_id', $this->_dirtyFields)) {
      $result['client_id'] = $this->clientId;
    }
    if (array_key_exists('client_secret', $this->_dirtyFields)) {
      $result['client_secret'] = $this->clientSecret;
    }
    return $result;
  }

  public function validate(): void
  {
  }
}
