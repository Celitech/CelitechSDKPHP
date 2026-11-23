<?php

declare(strict_types=1);

namespace Celitech\OAuth\Requests;

use Celitech\Core\Json\JsonSerializableType;
use Celitech\Core\Json\ReflectiveSerialization;
use Celitech\Core\Json\JsonProperty;

/**
 * The reflective deserializer reconstructs this model with `new static($values)`, so a subclass has
 * to keep a constructor it can call with one associative array.
 *
 * @phpstan-consistent-constructor
 */
class OAuthTokenRequest extends JsonSerializableType
{
  use ReflectiveSerialization;

  /**
   * @var value-of<\Celitech\OAuth\Types\GrantType>
   */
  #[JsonProperty('grant_type', required: true)]
  public string $grantType;

  #[JsonProperty('client_id', required: true)]
  public string $clientId;

  #[JsonProperty('client_secret', required: true)]
  public string $clientSecret;

  #[JsonProperty('scope', required: true)]
  public string $scope;

  /**
   * @param array{
   *   grantType: value-of<\Celitech\OAuth\Types\GrantType>,
   *   clientId: string,
   *   clientSecret: string,
   *   scope: string,
   * } $values
   */
  public function __construct(array $values)
  {
    $this->grantType = $values['grantType'];
    $this->clientId = $values['clientId'];
    $this->clientSecret = $values['clientSecret'];
    $this->scope = $values['scope'];

    $this->_dirtyFields = [
      'grant_type' => true,
      'client_id' => true,
      'client_secret' => true,
      'scope' => true
    ];
  }

  public function toMultipart(): array
  {
    $parts = [];
    $parts[] = [
      'name' => 'grant_type',
      'contents' => $this->grantType
    ];

    $parts[] = [
      'name' => 'client_id',
      'contents' => $this->clientId
    ];

    $parts[] = [
      'name' => 'client_secret',
      'contents' => $this->clientSecret
    ];

    $parts[] = [
      'name' => 'scope',
      'contents' => $this->scope
    ];

    return $parts;
  }

  public function validate(): void
  {
  }
}
