```php
<?php

use Celitech\Client;
use Celitech\Models\GetAccessTokenRequest;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');


$input = new Models\GetAccessTokenRequest(
  grantType: $grantType,
  clientId: "client_id",
  clientSecret: "client_secret"
);

$response = $sdk->OAuth->getAccessToken(
  input: $input
);

print_r($response);

```
