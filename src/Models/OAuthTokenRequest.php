<?php

declare(strict_types=1);

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class OAuthTokenRequest implements \JsonSerializable
{
  #[SerializedName('grant_type')]
  public GrantType $grantType;

  #[SerializedName('client_id')]
  public string $clientId;

  #[SerializedName('client_secret')]
  public string $clientSecret;

  #[SerializedName('scope')]
  public string $scope;

  public function __construct(
    GrantType $grantType,
    string $clientId,
    string $clientSecret,
    string $scope
  ) {
    $this->grantType = $grantType;
    $this->clientId = $clientId;
    $this->clientSecret = $clientSecret;
    $this->scope = $scope;
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
      clientId: $data['client_id'],
      clientSecret: $data['client_secret'],
      scope: $data['scope']
    );

    return $instance;
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [];
    $result['grant_type'] = $this->grantType;
    $result['client_id'] = $this->clientId;
    $result['client_secret'] = $this->clientSecret;
    $result['scope'] = $this->scope;
    return $result;
  }

  public function validate(): void
  {
  }
}
