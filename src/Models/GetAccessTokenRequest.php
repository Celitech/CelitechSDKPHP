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
   * @param array<string, mixed> $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    return new self(
      grantType: isset($data['grant_type']) &&
      (is_string($data['grant_type']) || is_int($data['grant_type']))
        ? GrantType::tryFrom($data['grant_type'])
        : null,
      clientId: $data['client_id'] ?? null,
      clientSecret: $data['client_secret'] ?? null
    );
  }

  /**
   * @return array<string, mixed>
   */
  public function jsonSerialize(): array
  {
    $result = [
      'grant_type' => $this->grantType,
      'client_id' => $this->clientId,
      'client_secret' => $this->clientSecret
    ];

    foreach (['grant_type', 'client_id', 'client_secret'] as $optionalKey) {
      if ($result[$optionalKey] === null) {
        unset($result[$optionalKey]);
      }
    }

    return $result;
  }
}
