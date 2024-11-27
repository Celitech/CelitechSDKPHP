# ESim

A list of all methods in the `ESim` service. Click on the method name to view detailed information about that method.

| Methods | Description |
| :------ | :---------- |
|[getESIM](#getesim)| Get eSIM Status |
|[getESIMDevice](#getesimdevice)| Get eSIM Device |
|[getESIMHistory](#getesimhistory)| Get eSIM History |
|[getESIMMac](#getesimmac)| Get eSIM MAC |

## getESIM

Get eSIM Status


- HTTP Method: `GET`
- Endpoint: `/esim`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| $iccid | string | ✅ | ID of the eSIM |

**Return Type**

`Models\GetEsimOkResponse`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $sdk->eSim->getEsim(
  iccid: "1111222233334444555000"
);

print_r($response);
```

## getESIMDevice

Get eSIM Device


- HTTP Method: `GET`
- Endpoint: `/esim/{iccid}/device`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| $iccid | string | ✅ | ID of the eSIM |

**Return Type**

`Models\GetEsimDeviceOkResponse`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $sdk->eSim->getEsimDevice(
  iccid: "1111222233334444555000"
);

print_r($response);
```

## getESIMHistory

Get eSIM History


- HTTP Method: `GET`
- Endpoint: `/esim/{iccid}/history`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| $iccid | string | ✅ | ID of the eSIM |

**Return Type**

`Models\GetEsimHistoryOkResponse`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $sdk->eSim->getEsimHistory(
  iccid: "1111222233334444555000"
);

print_r($response);
```

## getESIMMac

Get eSIM MAC


- HTTP Method: `GET`
- Endpoint: `/esim/{iccid}/mac`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| $iccid | string | ✅ | ID of the eSIM |

**Return Type**

`Models\GetEsimMacOkResponse`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $sdk->eSim->getEsimMac(
  iccid: "1111222233334444555000"
);

print_r($response);
```


