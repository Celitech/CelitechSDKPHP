```php
<?php

use Celitech\Client;
use Celitech\Models\GetAccessTokenRequest;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');


$input = new Models\GetAccessTokenRequest();

$response = $sdk->OAuth->getAccessToken(
  input: $input
);

print_r($response);

```
