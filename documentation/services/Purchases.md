# Purchases

A list of all methods in the `Purchases` service. Click on the method name to view detailed information about that method.

| Methods | Description |
| :------ | :---------- |
|[Create_Purchase](#create_purchase)| This endpoint is used to purchase a new eSIM by providing the package details. |
|[List_Purchases](#list_purchases)| This endpoint can be used to list all the successful purchases made between a given interval. |

## Create_Purchase

This endpoint is used to purchase a new eSIM by providing the package details.


- HTTP Method: `POST`
- Endpoint: `/purchases`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| input | Models\CreatePurchaseRequest | ✅ | This endpoint is used to purchase a new eSIM by providing the package details. |
| $accept | string | ✅ |  |

**Return Type**

`mixed`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;
use Celitech\Models\CreatePurchaseRequest;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');


$input = new Models\CreatePurchaseRequest();

$response = $sdk->purchases->createPurchase(
  input: $input,
  accept: "application/json"
);

print_r($response);
```

## List_Purchases

This endpoint can be used to list all the successful purchases made between a given interval.


- HTTP Method: `GET`
- Endpoint: `/purchases`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| $accept | string | ✅ |  |
| $purchaseId | string | ❌ | ID of the purchase |
| $iccid | string | ❌ | ID of the eSIM |
| $afterDate | string | ❌ | Start date of the interval for filtering purchases in the format 'yyyy-MM-dd' |
| $beforeDate | string | ❌ | End date of the interval for filtering purchases in the format 'yyyy-MM-dd' |
| $email | string | ❌ | Email associated to the purchase. |
| $referenceId | string | ❌ | The referenceId that was provided by the partner during the purchase or topup flow. |
| $afterCursor | string | ❌ | To get the next batch of results, use this parameter. It tells the API where to start fetching data after the last item you received. It helps you avoid repeats and efficiently browse through large sets of data. |
| $limit | string | ❌ | Maximum number of purchases to be returned in the response. The value must be greater than 0 and less than or equal to 100. If not provided, the default value is 20 |
| $after | string | ❌ | Epoch value representing the start of the time interval for filtering purchases |
| $before | string | ❌ | Epoch value representing the end of the time interval for filtering purchases |

**Return Type**

`mixed`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $sdk->purchases->listPurchases(
  accept: "application/json"
);

print_r($response);
```


