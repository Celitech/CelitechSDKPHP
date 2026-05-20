# History

A list of all methods in the `History` service. Click on the method name to view detailed information about that method.

| Methods | Description |
| :------ | :---------- |
|[Get_eSIM_History](#get_esim_history)| Get eSIM History |

## Get_eSIM_History

Get eSIM History


- HTTP Method: `GET`
- Endpoint: `/esim/{iccid}/history`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| $iccid | string | ✅ |  |
| $accept | string | ✅ |  |

**Return Type**

`mixed`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $sdk->history->getESimHistory(
  accept: "application/json",
  iccid: "iccid"
);

print_r($response);
```


