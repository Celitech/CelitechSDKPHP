# Destinations

A list of all methods in the `Destinations` service. Click on the method name to view detailed information about that method.

| Methods | Description |
| :------ | :---------- |
|[listDestinations](#listdestinations)| List Destinations |

## listDestinations

List Destinations


- HTTP Method: `GET`
- Endpoint: `/destinations`


**Return Type**

`Models\ListDestinationsOkResponse`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');

$response = $sdk->Destinations->listDestinations();

print_r($response);
```


