<?php

declare(strict_types=1);

namespace Celitech\Models;

enum GrantType: string
{
  case ClientCredentials = 'client_credentials';
}
