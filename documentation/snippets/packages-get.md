```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');

$response = $sdk->Packages->listPackages();

print_r($response);

```
