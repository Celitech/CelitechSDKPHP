```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');

$response = $sdk->ESim->getEsimMac(
  iccid: "1111222233334444555000"
);

print_r($response);

```
