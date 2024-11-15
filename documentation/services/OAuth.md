# OAuth

A list of all methods in the `OAuth` service. Click on the method name to view detailed information about that method.

| Methods | Description |
| :------ | :---------- |
|[getAccessToken](#getaccesstoken)| This endpoint was added by liblab |

## getAccessToken

This endpoint was added by liblab


- HTTP Method: `POST`
- Endpoint: `/oauth2/token`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| input | Models\GetAccessTokenRequest | ✅ | This endpoint was added by liblab |

**Return Type**

`Models\GetAccessTokenOkResponse`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;
use Celitech\Models\GetAccessTokenRequest;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');


$input = new Models\GetAccessTokenRequest();

$response = $sdk->OAuth->getAccessToken(
  input: $input
);

print_r($response);
```


