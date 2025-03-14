# Purchases

A list of all methods in the `Purchases` service. Click on the method name to view detailed information about that method.

| Methods | Description |
| :------ | :---------- |
|[createPurchaseV2](#createpurchasev2)| This endpoint is used to purchase a new eSIM by providing the package details. |
|[listPurchases](#listpurchases)| This endpoint can be used to list all the successful purchases made between a given interval. |
|[createPurchase](#createpurchase)| This endpoint is used to purchase a new eSIM by providing the package details. |
|[topUpESIM](#topupesim)| This endpoint is used to top-up an eSIM with the previously associated destination by providing an existing ICCID and the package details. The top-up is only feasible for eSIMs in "ENABLED" or "INSTALLED" state. You can check this state using the Get eSIM Status endpoint. |
|[editPurchase](#editpurchase)| This endpoint allows you to modify the dates of an existing package with a future activation start time. Editing can only be performed for packages that have not been activated, and it cannot change the package size. The modification must not change the package duration category to ensure pricing consistency. |
|[getPurchaseConsumption](#getpurchaseconsumption)| This endpoint can be called for consumption notifications (e.g. every 1 hour or when the user clicks a button). It returns the data balance (consumption) of purchased packages. |

## createPurchaseV2

This endpoint is used to purchase a new eSIM by providing the package details.


- HTTP Method: `POST`
- Endpoint: `/purchases/v2`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| input | Models\CreatePurchaseV2Request | ✅ | This endpoint is used to purchase a new eSIM by providing the package details. |

**Return Type**

`array`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;
use Celitech\Models\CreatePurchaseV2Request;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');


$input = new Models\CreatePurchaseV2Request(
  destination: "FRA",
  dataLimitInGb: 1,
  startDate: "2023-11-01",
  endDate: "2023-11-20",
  quantity: 1
);

$response = $sdk->purchases->createPurchaseV2(
  input: $input
);

print_r($response);
```

## listPurchases

This endpoint can be used to list all the successful purchases made between a given interval.


- HTTP Method: `GET`
- Endpoint: `/purchases`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| $iccid | string | ❌ | ID of the eSIM |
| $afterDate | string | ❌ | Start date of the interval for filtering purchases in the format 'yyyy-MM-dd' |
| $beforeDate | string | ❌ | End date of the interval for filtering purchases in the format 'yyyy-MM-dd' |
| $referenceId | string | ❌ | The referenceId that was provided by the partner during the purchase or topup flow. |
| $afterCursor | string | ❌ | To get the next batch of results, use this parameter. It tells the API where to start fetching data after the last item you received. It helps you avoid repeats and efficiently browse through large sets of data. |
| $limit | float | ❌ | Maximum number of purchases to be returned in the response. The value must be greater than 0 and less than or equal to 100. If not provided, the default value is 20 |
| $after | float | ❌ | Epoch value representing the start of the time interval for filtering purchases |
| $before | float | ❌ | Epoch value representing the end of the time interval for filtering purchases |

**Return Type**

`Models\ListPurchasesOkResponse`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $sdk->purchases->listPurchases();

print_r($response);
```

## createPurchase

This endpoint is used to purchase a new eSIM by providing the package details.


- HTTP Method: `POST`
- Endpoint: `/purchases`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| input | Models\CreatePurchaseRequest | ✅ | This endpoint is used to purchase a new eSIM by providing the package details. |

**Return Type**

`Models\CreatePurchaseOkResponse`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;
use Celitech\Models\CreatePurchaseRequest;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');


$input = new Models\CreatePurchaseRequest(
  destination: "FRA",
  dataLimitInGb: 1,
  startDate: "2023-11-01",
  endDate: "2023-11-20"
);

$response = $sdk->purchases->createPurchase(
  input: $input
);

print_r($response);
```

## topUpESIM

This endpoint is used to top-up an eSIM with the previously associated destination by providing an existing ICCID and the package details. The top-up is only feasible for eSIMs in "ENABLED" or "INSTALLED" state. You can check this state using the Get eSIM Status endpoint.


- HTTP Method: `POST`
- Endpoint: `/purchases/topup`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| input | Models\TopUpEsimRequest | ✅ | This endpoint is used to top-up an eSIM with the previously associated destination by providing an existing ICCID and the package details. The top-up is only feasible for eSIMs in "ENABLED" or "INSTALLED" state. You can check this state using the Get eSIM Status endpoint. |

**Return Type**

`Models\TopUpEsimOkResponse`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;
use Celitech\Models\TopUpEsimRequest;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');


$input = new Models\TopUpEsimRequest(
  iccid: "1111222233334444555000",
  dataLimitInGb: 1,
  startDate: "2023-11-01",
  endDate: "2023-11-20"
);

$response = $sdk->purchases->topUpEsim(
  input: $input
);

print_r($response);
```

## editPurchase

This endpoint allows you to modify the dates of an existing package with a future activation start time. Editing can only be performed for packages that have not been activated, and it cannot change the package size. The modification must not change the package duration category to ensure pricing consistency.


- HTTP Method: `POST`
- Endpoint: `/purchases/edit`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| input | Models\EditPurchaseRequest | ✅ | This endpoint allows you to modify the dates of an existing package with a future activation start time. Editing can only be performed for packages that have not been activated, and it cannot change the package size. The modification must not change the package duration category to ensure pricing consistency. |

**Return Type**

`Models\EditPurchaseOkResponse`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;
use Celitech\Models\EditPurchaseRequest;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');


$input = new Models\EditPurchaseRequest(
  purchaseId: "ae471106-c8b4-42cf-b83a-b061291f2922",
  startDate: "2023-11-01",
  endDate: "2023-11-20"
);

$response = $sdk->purchases->editPurchase(
  input: $input
);

print_r($response);
```

## getPurchaseConsumption

This endpoint can be called for consumption notifications (e.g. every 1 hour or when the user clicks a button). It returns the data balance (consumption) of purchased packages.


- HTTP Method: `GET`
- Endpoint: `/purchases/{purchaseId}/consumption`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| $purchaseId | string | ✅ | ID of the purchase |

**Return Type**

`Models\GetPurchaseConsumptionOkResponse`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $sdk->purchases->getPurchaseConsumption(
  purchaseId: "4973fa15-6979-4daa-9cf3-672620df819c"
);

print_r($response);
```


