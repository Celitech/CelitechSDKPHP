```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $sdk->Purchases->getPurchaseConsumption(
  purchaseId: "4973fa15-6979-4daa-9cf3-672620df819c"
);

print_r($response);

```
