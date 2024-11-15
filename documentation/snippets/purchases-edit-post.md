```php
<?php

use Celitech\Client;
use Celitech\Models\EditPurchaseRequest;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');


$input = new Models\EditPurchaseRequest(
  purchaseId: "ae471106-c8b4-42cf-b83a-b061291f2922",
  startDate: "2023-11-01",
  endDate: "2023-11-20"
);

$response = $sdk->Purchases->editPurchase(
  input: $input
);

print_r($response);

```
