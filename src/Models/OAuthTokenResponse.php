<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class OAuthTokenResponse implements \JsonSerializable
{
  #[SerializedName('access_token')]
  public ?string $accessToken;

  #[SerializedName('expires_in')]
  public ?int $expiresIn;

  /** @var array<string, true> Tracks which fields were explicitly set */
  private array $_dirtyFields = [];

  public function __construct(?string $accessToken = null, ?int $expiresIn = null)
  {
    $this->accessToken = $accessToken;
    $this->expiresIn = $expiresIn;

    if ($accessToken !== null) {
      $this->_dirtyFields['access_token'] = true;
    }
    if ($expiresIn !== null) {
      $this->_dirtyFields['expires_in'] = true;
    }
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
      accessToken: $data['access_token'] ?? null,
      expiresIn: $data['expires_in'] ?? null
    );
    $instance->_dirtyFields = [];
    foreach (['access_token', 'expires_in'] as $field) {
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
    if (array_key_exists('access_token', $this->_dirtyFields)) {
      $result['access_token'] = $this->accessToken;
    }
    if (array_key_exists('expires_in', $this->_dirtyFields)) {
      $result['expires_in'] = $this->expiresIn;
    }
    return $result;
  }

  public function validate(): void
  {
  }
}
