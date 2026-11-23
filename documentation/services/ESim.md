# ESim

A list of all methods in the `ESim` service. Click on the method name to view detailed information about that method.

| Methods                           | Description                                                                                                                                                    |
| :-------------------------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| [getEsim](#getesim)               | Get eSIM                                                                                                                                                       |
| [getEsimDevice](#getesimdevice)   | Get eSIM Device                                                                                                                                                |
| [getEsimHistory](#getesimhistory) | Get eSIM History                                                                                                                                               |
| [suspendEsim](#suspendesim)       | Suspends an eSIM to prevent abuse. All of the eSIM's packages are terminated and its connectivity is switched off. The eSIM is not refunded: the charge stays. |

## getEsim

Get eSIM

- HTTP Method: `GET`
- Endpoint: `/esim`

**Parameters**

| Name  | Type   | Required | Description    |
| :---- | :----- | :------- | :------------- |
| iccid | string | ✅        | ID of the eSIM |

**Return Type**

[`GetEsimResponse`](../models/GetEsimResponse.md)

**Example Usage Code Snippet**

```php
<?php

use Celitech\Client;
use Celitech\ESim\Requests\GetEsimRequest;

$client = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $client->eSim->getEsim(
    new GetEsimRequest([
        'iccid' => '1111222233334444555000',
    ]),
);

print_r($response);
```

## getEsimDevice

Get eSIM Device

- HTTP Method: `GET`
- Endpoint: `/esim/{iccid}/device`

**Parameters**

| Name   | Type   | Required | Description    |
| :----- | :----- | :------- | :------------- |
| $iccid | string | ✅        | ID of the eSIM |

**Return Type**

[`GetEsimDeviceResponse`](../models/GetEsimDeviceResponse.md)

**Example Usage Code Snippet**

```php
<?php

use Celitech\Client;

$client = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $client->eSim->getEsimDevice(
    '1111222233334444555000',
);

print_r($response);
```

## getEsimHistory

Get eSIM History

- HTTP Method: `GET`
- Endpoint: `/esim/{iccid}/history`

**Parameters**

| Name   | Type   | Required | Description    |
| :----- | :----- | :------- | :------------- |
| $iccid | string | ✅        | ID of the eSIM |

**Return Type**

[`GetEsimHistoryResponse`](../models/GetEsimHistoryResponse.md)

**Example Usage Code Snippet**

```php
<?php

use Celitech\Client;

$client = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $client->eSim->getEsimHistory(
    '1111222233334444555000',
);

print_r($response);
```

## suspendEsim

Suspends an eSIM to prevent abuse. All of the eSIM's packages are terminated and its connectivity is switched off. The eSIM is not refunded: the charge stays.

- HTTP Method: `POST`
- Endpoint: `/esim/{iccid}/suspend`

**Parameters**

| Name           | Type   | Required | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |
| :------------- | :----- | :------- | :----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| $iccid         | string | ✅        | ID of the eSIM                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |
| idempotencyKey | string | ❌        | Optional. A unique value (for example a UUID) that makes this request safe to retry. If a request with the same `Idempotency-Key` and the same body succeeds, repeating it returns the original response instead of performing the action again, and the response includes the header `Idempotent-Replayed: true`. Keys are scoped to your credentials and remembered for 3 days. Only successful (2xx) responses are remembered, so a request that failed can be retried with the same key. Reusing a key with a different request returns `422`; sending it while the original request is still being processed returns `409`. If the idempotency store is temporarily unavailable, the request is not processed and returns `503`; retry it. Must be 1 to 255 printable ASCII characters, without spaces. |

**Return Type**

[`SuspendEsimResponse`](../models/SuspendEsimResponse.md)

**Example Usage Code Snippet**

```php
<?php

use Celitech\Client;
use Celitech\ESim\Requests\SuspendEsimRequest;

$client = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $client->eSim->suspendEsim(
    '1111222233334444555000',
    new SuspendEsimRequest([
        'idempotencyKey' => '8f14e45f-ceea-4672-a1d9-0a5a3c2f1e17',
    ]),
);

print_r($response);
```
