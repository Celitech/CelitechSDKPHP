```php
<?php

use Celitech\Client;
use Celitech\Models\CreatePurchaseV2Request;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');


$input = new Models\CreatePurchaseV2Request(
  destination: "FRA",
  dataLimitInGb: 1,
  quantity: 1
);

$response = $sdk->purchases->createPurchaseV2(
  input: $input
);

print_r($response);

```
