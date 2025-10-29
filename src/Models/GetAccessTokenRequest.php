<?php

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

    public function __construct(?GrantType $grantType = null, ?string $clientId = null, ?string $clientSecret = null)
    {
        $this->grantType = $grantType;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * Deserialize from array
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            grantType: isset($data['grant_type']) && is_string($data['grant_type'])
                ? GrantType::from($data['grant_type'])
                : null,
            clientId: $data['client_id'] ?? null,
            clientSecret: $data['client_secret'] ?? null
        );
    }

    /**
     * Serialize to JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'grant_type' => $this->grantType,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];
    }
}
