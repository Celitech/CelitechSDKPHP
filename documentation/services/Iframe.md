# Iframe

A list of all methods in the `Iframe` service. Click on the method name to view detailed information about that method.

| Methods | Description |
| :------ | :---------- |
|[token](#token)| Generate a new token to be used in the iframe |

## token

Generate a new token to be used in the iframe


- HTTP Method: `GET`
- Endpoint: `/iframe/token`


**Return Type**

`Models\TokenOkResponse`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $sdk->iframe->token();

print_r($response);
```


