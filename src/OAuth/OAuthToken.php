<?php

namespace Celitech\OAuth;

class OAuthToken
{
    public string $accessToken;
    public array $scopes;
    public ?int $expiresAt;

    public function __construct(string $accessToken, array $scopes, ?int $expiresAt)
    {
        $this->accessToken = $accessToken;
        $this->scopes = $scopes;
        $this->expiresAt = $expiresAt;
    }
}
