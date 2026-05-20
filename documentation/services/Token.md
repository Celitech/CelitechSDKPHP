# Token

A list of all methods in the `Token` service. Click on the method name to view detailed information about that method.

| Methods | Description |
| :------ | :---------- |
|[Generate_Token](#generate_token)| Generate a new token to be used in the iFrame |

## Generate_Token

Generate a new token to be used in the iFrame


- HTTP Method: `POST`
- Endpoint: `/iframe/token`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| $accept | string | ✅ |  |

**Return Type**

`mixed`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $sdk->token->generateToken(
  accept: "application/json"
);

print_r($response);
```


