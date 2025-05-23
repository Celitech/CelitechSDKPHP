<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class GetAccessTokenRequest
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
}
