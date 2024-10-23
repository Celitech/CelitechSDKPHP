```php
<?php

use Celitech\Client;
use Celitech\Models\CreatePurchaseRequest;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');


$input = new Models\CreatePurchaseRequest(
  destination: "destination",
  dataLimitInGb: 123,
  startDate: "startDate",
  endDate: "endDate",
  email: "email",
  referenceId: "referenceId",
  networkBrand: "networkBrand",
  startTime: 123,
  endTime: 123
);

$response = $sdk->Purchases->createPurchase(
  input: $input
);

print_r($response);

```
