```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $sdk->eSim->getEsim(
  iccid: "1111222233334444555000"
);

print_r($response);

```
