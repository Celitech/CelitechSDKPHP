# Topup

A list of all methods in the `Topup` service. Click on the method name to view detailed information about that method.

| Methods | Description |
| :------ | :---------- |
|[Top_up_eSIM](#top_up_esim)| This endpoint is used to top-up an existing eSIM with the previously associated destination by providing its ICCID and package details. To determine if an eSIM can be topped up, use the Get eSIM endpoint, which returns the `isTopUpAllowed` flag. |

## Top_up_eSIM

This endpoint is used to top-up an existing eSIM with the previously associated destination by providing its ICCID and package details. To determine if an eSIM can be topped up, use the Get eSIM endpoint, which returns the `isTopUpAllowed` flag.


- HTTP Method: `POST`
- Endpoint: `/purchases/topup`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| input | Models\TopUpESimRequest | ✅ | This endpoint is used to top-up an existing eSIM with the previously associated destination by providing its ICCID and package details. To determine if an eSIM can be topped up, use the Get eSIM endpoint, which returns the `isTopUpAllowed` flag. |
| $accept | string | ✅ |  |

**Return Type**

`mixed`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;
use Celitech\Models\TopUpESimRequest;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');


$input = new Models\TopUpESimRequest();

$response = $sdk->topup->topUpESim(
  input: $input,
  accept: "application/json"
);

print_r($response);
```


