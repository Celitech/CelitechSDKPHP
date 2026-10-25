<?php

declare(strict_types=1);

namespace Celitech\OAuth\Types;

enum GrantType: string
{
  case ClientCredentials = 'client_credentials';
}
