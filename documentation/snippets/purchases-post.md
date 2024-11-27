```php
<?php

use Celitech\Client;
use Celitech\Models\CreatePurchaseRequest;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');


$input = new Models\CreatePurchaseRequest(
  destination: "FRA",
  dataLimitInGb: 1,
  startDate: "2023-11-01",
  endDate: "2023-11-20"
);

$response = $sdk->purchases->createPurchase(
  input: $input
);

print_r($response);

```
