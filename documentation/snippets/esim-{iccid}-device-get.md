```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');

$response = $sdk->ESim->getEsimDevice(
  iccid: "iccid"
);

print_r($response);

```
