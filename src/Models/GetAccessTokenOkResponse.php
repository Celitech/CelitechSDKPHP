<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class GetAccessTokenOkResponse implements \JsonSerializable
{
    #[SerializedName('access_token')]
    public ?string $accessToken;

    #[SerializedName('token_type')]
    public ?string $tokenType;

    #[SerializedName('expires_in')]
    public ?int $expiresIn;

    public function __construct(?string $accessToken = null, ?string $tokenType = null, ?int $expiresIn = null)
    {
        $this->accessToken = $accessToken;
        $this->tokenType = $tokenType;
        $this->expiresIn = $expiresIn;
    }

    /**
     * Deserialize from array
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            accessToken: $data['access_token'] ?? null,
            tokenType: $data['token_type'] ?? null,
            expiresIn: $data['expires_in'] ?? null
        );
    }

    /**
     * Serialize to JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'access_token' => $this->accessToken,
            'token_type' => $this->tokenType,
            'expires_in' => $this->expiresIn,
        ];
    }
}
