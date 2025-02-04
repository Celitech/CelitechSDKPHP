<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

class TokenOkResponse
{
    /**
     * The generated token
     */
    #[SerializedName('token')]
    public ?string $token;

    public function __construct(?string $token = null)
    {
        $this->token = $token;
    }
}
