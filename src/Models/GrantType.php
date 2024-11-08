<?php

namespace Celitech\Models;

use Symfony\Component\Serializer\Annotation\SerializedName;

enum GrantType: string
{
    case ClientCredentials = 'client_credentials';
}
