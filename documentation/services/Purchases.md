# Purchases

A list of all methods in the `Purchases` service. Click on the method name to view detailed information about that method.

| Methods | Description |
| :------ | :---------- |
|[createPurchaseV2](#createpurchasev2)| This endpoint is used to purchase a new eSIM by providing the package details. |
|[listPurchases](#listpurchases)| This endpoint can be used to list all the successful purchases made between a given interval. |
|[createPurchase](#createpurchase)| This endpoint is used to purchase a new eSIM by providing the package details. |
|[topUpESIM](#topupesim)| This endpoint is used to top-up an existing eSIM with the previously associated destination by providing its ICCID and package details. To determine if an eSIM can be topped up, use the Get eSIM endpoint, which returns the `isTopUpAllowed` flag. |
|[editPurchase](#editpurchase)| This endpoint allows you to modify the validity dates of an existing purchase.   **Behavior:** - If the purchase has **not yet been activated**, both the start and end dates can be updated.   - If the purchase is **already active**, only the **end date** can be updated, while the **start date must remain unchanged** (and should be passed as originally set).   - Updates must comply with the same pricing structure; the modification cannot alter the package size or change its duration category.   The end date can be extended or shortened as long as it adheres to the same pricing category and does not exceed the allowed duration limits.  |
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
  duration: 30,
  quantity: 1,
  email: "example@domain.com",
  referenceId: "abc111222333444",
  networkBrand: "CELITECH",
  emailBrand: "CELITECH"
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
| $purchaseId | string | ❌ | ID of the purchase |
| $iccid | string | ❌ | ID of the eSIM |
| $afterDate | string | ❌ | Start date of the interval for filtering purchases in the format 'yyyy-MM-dd' |
| $beforeDate | string | ❌ | End date of the interval for filtering purchases in the format 'yyyy-MM-dd' |
| $email | string | ❌ | Email associated to the purchase. |
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

$response = $sdk->purchases->listPurchases(
  purchaseId: "4973fa15-6979-4daa-9cf3-672620df819c",
  iccid: "1111222233334444555000",
  afterDate: "2023-11-01",
  beforeDate: "2023-11-20",
  email: "example@gmail.com",
  referenceId: "abc111222333444",
  afterCursor: "Y3JlYXRlZEF0OjE1OTk0OTMwOTgsZGVzdGluYXRpb246QVVTLG1pbkRheXM6MCxkYXRhTGltaXRJbkJ5dGVzOjUzNjg3MDkxMjA",
  limit: 20,
  after: 0.75,
  before: 0.4
);

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
  endDate: "2023-11-20",
  email: "example@domain.com",
  referenceId: "abc111222333444",
  networkBrand: "CELITECH",
  emailBrand: "CELITECH",
  startTime: 1.15,
  endTime: 5.74
);

$response = $sdk->purchases->createPurchase(
  input: $input
);

print_r($response);
```

## topUpESIM

This endpoint is used to top-up an existing eSIM with the previously associated destination by providing its ICCID and package details. To determine if an eSIM can be topped up, use the Get eSIM endpoint, which returns the `isTopUpAllowed` flag.


- HTTP Method: `POST`
- Endpoint: `/purchases/topup`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| input | Models\TopUpEsimRequest | ✅ | This endpoint is used to top-up an existing eSIM with the previously associated destination by providing its ICCID and package details. To determine if an eSIM can be topped up, use the Get eSIM endpoint, which returns the `isTopUpAllowed` flag. |

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
  endDate: "2023-11-20",
  duration: 30,
  email: "example@domain.com",
  referenceId: "abc111222333444",
  emailBrand: "CELITECH",
  startTime: 2.83,
  endTime: 1.27
);

$response = $sdk->purchases->topUpEsim(
  input: $input
);

print_r($response);
```

## editPurchase

This endpoint allows you to modify the validity dates of an existing purchase.   **Behavior:** - If the purchase has **not yet been activated**, both the start and end dates can be updated.   - If the purchase is **already active**, only the **end date** can be updated, while the **start date must remain unchanged** (and should be passed as originally set).   - Updates must comply with the same pricing structure; the modification cannot alter the package size or change its duration category.   The end date can be extended or shortened as long as it adheres to the same pricing category and does not exceed the allowed duration limits. 


- HTTP Method: `POST`
- Endpoint: `/purchases/edit`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| input | Models\EditPurchaseRequest | ✅ | This endpoint allows you to modify the validity dates of an existing purchase.   **Behavior:** - If the purchase has **not yet been activated**, both the start and end dates can be updated.   - If the purchase is **already active**, only the **end date** can be updated, while the **start date must remain unchanged** (and should be passed as originally set).   - Updates must comply with the same pricing structure; the modification cannot alter the package size or change its duration category.   The end date can be extended or shortened as long as it adheres to the same pricing category and does not exceed the allowed duration limits.  |

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
  endDate: "2023-11-20",
  startTime: 2.17,
  endTime: 2.25
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


