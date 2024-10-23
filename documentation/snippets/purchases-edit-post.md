```php
<?php

use Celitech\Client;
use Celitech\Models\EditPurchaseRequest;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');


$input = new Models\EditPurchaseRequest(
  purchaseId: "purchaseId",
  startDate: "startDate",
  endDate: "endDate",
  startTime: 123,
  endTime: 123
);

$response = $sdk->Purchases->editPurchase(
  input: $input
);

print_r($response);

```
