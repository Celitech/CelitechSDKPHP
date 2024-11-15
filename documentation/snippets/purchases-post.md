```php
<?php

use Celitech\Client;
use Celitech\Models\CreatePurchaseRequest;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');


$input = new Models\CreatePurchaseRequest(
  destination: "FRA",
  dataLimitInGb: 1,
  startDate: "2023-11-01",
  endDate: "2023-11-20"
);

$response = $sdk->Purchases->createPurchase(
  input: $input
);

print_r($response);

```
