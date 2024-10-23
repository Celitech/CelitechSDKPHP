```php
<?php

use Celitech\Client;
use Celitech\Models\TopUpEsimRequest;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');


$input = new Models\TopUpEsimRequest(
  iccid: "iccid",
  dataLimitInGb: 123,
  startDate: "startDate",
  endDate: "endDate",
  email: "email",
  referenceId: "referenceId",
  startTime: 123,
  endTime: 123
);

$response = $sdk->Purchases->topUpEsim(
  input: $input
);

print_r($response);

```
