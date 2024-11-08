<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class GetAccessTokenOkResponse
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
}
