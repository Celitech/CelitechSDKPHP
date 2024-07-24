```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');

$response = $sdk->ESim->getEsimMac(
  iccid: "iccid"
);

print_r($response);

```
