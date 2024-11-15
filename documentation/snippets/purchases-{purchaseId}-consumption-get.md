```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');

$response = $sdk->Purchases->getPurchaseConsumption(
  purchaseId: "4973fa15-6979-4daa-9cf3-672620df819c"
);

print_r($response);

```
