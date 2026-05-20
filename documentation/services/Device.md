# Device

A list of all methods in the `Device` service. Click on the method name to view detailed information about that method.

| Methods | Description |
| :------ | :---------- |
|[Get_eSIM_Device](#get_esim_device)| Get eSIM Device |

## Get_eSIM_Device

Get eSIM Device


- HTTP Method: `GET`
- Endpoint: `/esim/{iccid}/device`

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

$response = $sdk->device->getESimDevice(
  accept: "application/json",
  iccid: "iccid"
);

print_r($response);
```


