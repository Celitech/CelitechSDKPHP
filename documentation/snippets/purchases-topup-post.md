```php
<?php

use Celitech\Client;
use Celitech\Models\TopUpEsimRequest;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');


$input = new Models\TopUpEsimRequest(
  iccid: "1111222233334444555000",
  dataLimitInGb: 1,
  startDate: "2023-11-01",
  endDate: "2023-11-20"
);

$response = $sdk->purchases->topUpEsim(
  input: $input
);

print_r($response);

```
