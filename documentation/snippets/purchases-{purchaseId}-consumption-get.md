```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');

$response = $sdk->Purchases->getPurchaseConsumption(
  purchaseId: "purchaseId"
);

print_r($response);

```
