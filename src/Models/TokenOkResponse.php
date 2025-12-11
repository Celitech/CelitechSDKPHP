<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class TokenOkResponse implements \JsonSerializable
{
    /**
     * The generated token
     */
    #[SerializedName('token')]
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Deserialize from array
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(token: $data['token'] ?? null);
    }

    /**
     * Serialize to JSON
     */
    public function jsonSerialize(): array
    {
        return [
            'token' => $this->token,
        ];
    }
}
