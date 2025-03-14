```php
<?php

use Celitech\Client;
use Celitech\Models\CreatePurchaseV2Request;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');


$input = new Models\CreatePurchaseV2Request(
  destination: "FRA",
  dataLimitInGb: 1,
  startDate: "2023-11-01",
  endDate: "2023-11-20",
  quantity: 1
);

$response = $sdk->purchases->createPurchaseV2(
  input: $input
);

print_r($response);

```
