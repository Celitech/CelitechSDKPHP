# Purchases

A list of all methods in the `Purchases` service. Click on the method name to view detailed information about that method.

| Methods | Description |
| :------ | :---------- |
|[createPurchaseV2](#createpurchasev2)| This endpoint lets you purchase a new eSIM by providing the package details. You must include **either**:   - Both `startDate` and `endDate` (to set a fixed date range),     **or**   - `duration` (to set how many days the eSIM will be active). These options cannot be used together, only one of them should be provided.  |
|[listPurchases](#listpurchases)| This endpoint can be used to list all the successful purchases made between a given interval. |
|[topUpESIM](#topupesim)| This endpoint lets you top up an existing eSIM by providing its ICCID and the new package details.   The destination must be the same as the one used in the original purchase. Top-up is only allowed for eSIMs that are in the **"ENABLED"** or **"INSTALLED"** state.   You can check the current state using the **Get eSIM Status** endpoint. You must include **either**:   - Both `startDate` and `endDate` (to set a fixed date range),     **or**   - `duration` (to set how many days the eSIM will be active). These options cannot be used together — only one of them should be provided.  |
|[editPurchase](#editpurchase)| This endpoint allows you to modify the dates of an existing package with a future activation start time. Editing can only be performed for packages that have not been activated, and it cannot change the package size. The modification must not change the package duration category to ensure pricing consistency. Duration based packages cannot be edited. |
|[getPurchaseConsumption](#getpurchaseconsumption)| This endpoint can be called for consumption notifications (e.g. every 1 hour or when the user clicks a button). It returns the data balance (consumption) of purchased packages. |

## createPurchaseV2

This endpoint lets you purchase a new eSIM by providing the package details. You must include **either**:   - Both `startDate` and `endDate` (to set a fixed date range),     **or**   - `duration` (to set how many days the eSIM will be active). These options cannot be used together, only one of them should be provided. 


- HTTP Method: `POST`
- Endpoint: `/purchases/v2`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| input | Models\CreatePurchaseV2Request | ✅ | This endpoint lets you purchase a new eSIM by providing the package details. You must include **either**:   - Both `startDate` and `endDate` (to set a fixed date range),     **or**   - `duration` (to set how many days the eSIM will be active). These options cannot be used together, only one of them should be provided.  |

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

## topUpESIM

This endpoint lets you top up an existing eSIM by providing its ICCID and the new package details.   The destination must be the same as the one used in the original purchase. Top-up is only allowed for eSIMs that are in the **"ENABLED"** or **"INSTALLED"** state.   You can check the current state using the **Get eSIM Status** endpoint. You must include **either**:   - Both `startDate` and `endDate` (to set a fixed date range),     **or**   - `duration` (to set how many days the eSIM will be active). These options cannot be used together — only one of them should be provided. 


- HTTP Method: `POST`
- Endpoint: `/purchases/topup`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| input | Models\TopUpEsimRequest | ✅ | This endpoint lets you top up an existing eSIM by providing its ICCID and the new package details.   The destination must be the same as the one used in the original purchase. Top-up is only allowed for eSIMs that are in the **"ENABLED"** or **"INSTALLED"** state.   You can check the current state using the **Get eSIM Status** endpoint. You must include **either**:   - Both `startDate` and `endDate` (to set a fixed date range),     **or**   - `duration` (to set how many days the eSIM will be active). These options cannot be used together — only one of them should be provided.  |

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
  dataLimitInGb: 1
);

$response = $sdk->purchases->topUpEsim(
  input: $input
);

print_r($response);
```

## editPurchase

This endpoint allows you to modify the dates of an existing package with a future activation start time. Editing can only be performed for packages that have not been activated, and it cannot change the package size. The modification must not change the package duration category to ensure pricing consistency. Duration based packages cannot be edited.


- HTTP Method: `POST`
- Endpoint: `/purchases/edit`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| input | Models\EditPurchaseRequest | ✅ | This endpoint allows you to modify the dates of an existing package with a future activation start time. Editing can only be performed for packages that have not been activated, and it cannot change the package size. The modification must not change the package duration category to ensure pricing consistency. Duration based packages cannot be edited. |

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


