# Esim

A list of all methods in the `Esim` service. Click on the method name to view detailed information about that method.

| Methods | Description |
| :------ | :---------- |
|[Get_eSIM](#get_esim)| Get eSIM |

## Get_eSIM

Get eSIM


- HTTP Method: `GET`
- Endpoint: `/esim`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| $accept | string | ✅ |  |
| $iccid | string | ❌ | ID of the eSIM |

**Return Type**

`mixed`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $sdk->esim->getESim(
  accept: "application/json"
);

print_r($response);
```


