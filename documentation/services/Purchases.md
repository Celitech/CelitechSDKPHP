# Purchases

A list of all methods in the `Purchases` service. Click on the method name to view detailed information about that method.

| Methods                                           | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |
| :------------------------------------------------ | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| [createPurchaseV2](#createpurchasev2)             | This endpoint is used to purchase a new eSIM by providing the package details.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| [listPurchases](#listpurchases)                   | This endpoint can be used to list all the successful purchases made between a given interval.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            |
| [createPurchase](#createpurchase)                 | This endpoint is used to purchase a new eSIM by providing the package details.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| [topUpEsim](#topupesim)                           | This endpoint is used to top-up an existing eSIM with the previously associated destination by providing its ICCID and package details. To determine if an eSIM can be topped up, use the Get eSIM endpoint, which returns the `isTopUpAllowed` flag.                                                                                                                                                                                                                                                                                                                                                                                                    |
| [editPurchase](#editpurchase)                     | This endpoint allows you to modify the validity dates of an existing purchase. **Behavior:** - If the purchase has **not yet been activated**, both the start and end dates can be updated. - If the purchase is **already active**, only the **end date** can be updated, while the **start date must remain unchanged** (and should be passed as originally set). - Updates must comply with the same pricing structure; the modification cannot alter the package size or change its duration category. The end date can be extended or shortened as long as it adheres to the same pricing category and does not exceed the allowed duration limits. |
| [getPurchaseConsumption](#getpurchaseconsumption) | This endpoint can be called for consumption notifications (e.g. every 1 hour or when the user clicks a button). It returns the data balance (consumption) of purchased packages.                                                                                                                                                                                                                                                                                                                                                                                                                                                                         |

## createPurchaseV2

This endpoint is used to purchase a new eSIM by providing the package details.

- HTTP Method: `POST`
- Endpoint: `/purchases/v2`

**Parameters**

| Name           | Type                                                                                                    | Required | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |
| :------------- | :------------------------------------------------------------------------------------------------------ | :------- | :----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| destination    | string                                                                                                  | ✅        | ISO representation of the package's destination. Supports both ISO2 (e.g., 'FR') and ISO3 (e.g., 'FRA') country codes.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       |
| dataLimitInGb  | float                                                                                                   | ✅        | Size of the package in GB. The available options are 0.5, 1, 2, 3, 5, 8, 20, 50GB. Use `-1` to purchase an unlimited (date-based) package — provide `startDate`/`endDate` spanning 3 to 30 days (`duration` is not supported for unlimited packages).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        |
| startDate      | DateTime                                                                                                | ❌        | Start date of the package's validity in the format 'yyyy-MM-dd'. This date can be set to the current day or any day within the next 12 months.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |
| endDate        | DateTime                                                                                                | ❌        | End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| duration       | float                                                                                                   | ❌        | Duration of the package in days. Available values are 1, 2, 7, 14, 30, or 90. Either provide startDate/endDate or duration. Not supported for unlimited packages (`dataLimitInGB` = -1), which are date-based — provide startDate/endDate instead.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| quantity       | float                                                                                                   | ✅        | Number of eSIMs to purchase.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| email          | string                                                                                                  | ❌        | Email address where the purchase confirmation email will be sent (including QR Code & activation steps)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      |
| referenceId    | string                                                                                                  | ❌        | An identifier provided by the partner to link this purchase to their booking or transaction for analytics and debugging purposes. Letters, digits, `-`, `_`, `.`, `@` and `+` are allowed (so an email address works), up to 255 characters.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| networkBrand   | string                                                                                                  | ❌        | Customize the network brand of the issued eSIM. The `networkBrand` parameter cannot exceed 15 characters in length and must contain only letters, numbers, dots (.), ampersands (&), and spaces. This feature is available to platforms with Diamond tier only.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |
| emailBrand     | string                                                                                                  | ❌        | Customize the email subject brand. The `emailBrand` parameter cannot exceed 25 characters in length and must contain only letters, numbers, and spaces. This feature is available to platforms with Diamond tier only.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       |
| language       | [CreatePurchaseV2RequestLanguage](../models/CreatePurchaseV2Request.md#createpurchasev2requestlanguage) | ❌        | Language of the confirmation email sent to the customer.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| idempotencyKey | string                                                                                                  | ❌        | Optional. A unique value (for example a UUID) that makes this request safe to retry. If a request with the same `Idempotency-Key` and the same body succeeds, repeating it returns the original response instead of performing the action again, and the response includes the header `Idempotent-Replayed: true`. Keys are scoped to your credentials and remembered for 3 days. Only successful (2xx) responses are remembered, so a request that failed can be retried with the same key. Reusing a key with a different request returns `422`; sending it while the original request is still being processed returns `409`. If the idempotency store is temporarily unavailable, the request is not processed and returns `503`; retry it. Must be 1 to 255 printable ASCII characters, without spaces. |

**Return Type**

[`CreatePurchaseV2ResponseItem[]`](../models/CreatePurchaseV2ResponseItem.md)

**Example Usage Code Snippet**

```php
<?php

use Celitech\Client;
use Celitech\Purchases\Requests\CreatePurchaseV2Request;

$client = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $client->purchases->createPurchaseV2(
    new CreatePurchaseV2Request([
        'idempotencyKey' => '8f14e45f-ceea-4672-a1d9-0a5a3c2f1e17',
        'destination' => 'FRA',
        'dataLimitInGb' => 1,
        'quantity' => 1,
    ]),
);

print_r($response);
```

## listPurchases

This endpoint can be used to list all the successful purchases made between a given interval.

- HTTP Method: `GET`
- Endpoint: `/purchases`

**Parameters**

| Name        | Type     | Required | Description                                                                                                                                                                                                         |
| :---------- | :------- | :------- | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| purchaseId  | string   | ❌        | ID of the purchase                                                                                                                                                                                                  |
| iccid       | string   | ❌        | ID of the eSIM                                                                                                                                                                                                      |
| afterDate   | DateTime | ❌        | Start date of the interval for filtering purchases in the format 'yyyy-MM-dd'                                                                                                                                       |
| beforeDate  | DateTime | ❌        | End date of the interval for filtering purchases in the format 'yyyy-MM-dd'                                                                                                                                         |
| email       | string   | ❌        | Email associated to the purchase.                                                                                                                                                                                   |
| referenceId | string   | ❌        | The referenceId that was provided by the partner during the purchase or topup flow.                                                                                                                                 |
| afterCursor | string   | ❌        | To get the next batch of results, use this parameter. It tells the API where to start fetching data after the last item you received. It helps you avoid repeats and efficiently browse through large sets of data. |
| limit       | float    | ❌        | Maximum number of purchases to be returned in the response. The value must be greater than 0 and less than or equal to 100. If not provided, the default value is 20                                                |
| after       | float    | ❌        | Epoch value representing the start of the time interval for filtering purchases                                                                                                                                     |
| before      | float    | ❌        | Epoch value representing the end of the time interval for filtering purchases                                                                                                                                       |

**Return Type**

[`ListPurchasesResponse`](../models/ListPurchasesResponse.md)

**Example Usage Code Snippet**

```php
<?php

use Celitech\Client;
use Celitech\Purchases\Requests\ListPurchasesRequest;

$client = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $client->purchases->listPurchases(
    new ListPurchasesRequest([
        'purchaseId' => '4973fa15-6979-4daa-9cf3-672620df819c',
        'iccid' => '1111222233334444555000',
        'afterDate' => new DateTime('2023-11-01'),
        'beforeDate' => new DateTime('2023-11-20'),
        'email' => 'example@gmail.com',
        'referenceId' => 'abc111222333444',
        'afterCursor' => 'Y3JlYXRlZEF0OjE1OTk0OTMwOTgsZGVzdGluYXRpb246QVVTLG1pbkRheXM6MCxkYXRhTGltaXRJbkJ5dGVzOjUzNjg3MDkxMjA',
        'limit' => 20,
    ]),
);

print_r($response);
```

## createPurchase

This endpoint is used to purchase a new eSIM by providing the package details.

- HTTP Method: `POST`
- Endpoint: `/purchases`

**Parameters**

| Name           | Type                                                                                              | Required | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |
| :------------- | :------------------------------------------------------------------------------------------------ | :------- | :----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| destination    | string                                                                                            | ✅        | ISO representation of the package's destination. Supports both ISO2 (e.g., 'FR') and ISO3 (e.g., 'FRA') country codes.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       |
| dataLimitInGb  | float                                                                                             | ✅        | Size of the package in GB. The available options are 0.5, 1, 2, 3, 5, 8, 20, 50GB. Use `-1` to purchase an unlimited (date-based) package — provide `startDate`/`endDate` spanning 3 to 30 days.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| startDate      | DateTime                                                                                          | ✅        | Start date of the package's validity in the format 'yyyy-MM-dd'. This date can be set to the current day or any day within the next 12 months.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |
| endDate        | DateTime                                                                                          | ✅        | End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| email          | string                                                                                            | ❌        | Email address where the purchase confirmation email will be sent (including QR Code & activation steps)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      |
| referenceId    | string                                                                                            | ❌        | An identifier provided by the partner to link this purchase to their booking or transaction for analytics and debugging purposes. Letters, digits, `-`, `_`, `.`, `@` and `+` are allowed (so an email address works), up to 255 characters.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| networkBrand   | string                                                                                            | ❌        | Customize the network brand of the issued eSIM. The `networkBrand` parameter cannot exceed 15 characters in length and must contain only letters, numbers, dots (.), ampersands (&), and spaces. This feature is available to platforms with Diamond tier only.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |
| emailBrand     | string                                                                                            | ❌        | Customize the email subject brand. The `emailBrand` parameter cannot exceed 25 characters in length and must contain only letters, numbers, and spaces. This feature is available to platforms with Diamond tier only.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       |
| language       | [CreatePurchaseRequestLanguage](../models/CreatePurchaseRequest.md#createpurchaserequestlanguage) | ❌        | Language of the confirmation email sent to the customer.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| startTime      | float                                                                                             | ❌        | Epoch value representing the start time of the package's validity. This timestamp can be set to the current time or any time within the next 12 months.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      |
| endTime        | float                                                                                             | ❌        | Epoch value representing the end time of the package's validity. End time can be maximum 90 days after Start time.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| idempotencyKey | string                                                                                            | ❌        | Optional. A unique value (for example a UUID) that makes this request safe to retry. If a request with the same `Idempotency-Key` and the same body succeeds, repeating it returns the original response instead of performing the action again, and the response includes the header `Idempotent-Replayed: true`. Keys are scoped to your credentials and remembered for 3 days. Only successful (2xx) responses are remembered, so a request that failed can be retried with the same key. Reusing a key with a different request returns `422`; sending it while the original request is still being processed returns `409`. If the idempotency store is temporarily unavailable, the request is not processed and returns `503`; retry it. Must be 1 to 255 printable ASCII characters, without spaces. |

**Return Type**

[`CreatePurchaseResponse`](../models/CreatePurchaseResponse.md)

**Example Usage Code Snippet**

```php
<?php

use Celitech\Client;
use Celitech\Purchases\Requests\CreatePurchaseRequest;

$client = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $client->purchases->createPurchase(
    new CreatePurchaseRequest([
        'idempotencyKey' => '8f14e45f-ceea-4672-a1d9-0a5a3c2f1e17',
        'destination' => 'FRA',
        'dataLimitInGb' => 1,
        'startDate' => new DateTime('2023-11-01'),
        'endDate' => new DateTime('2023-11-20'),
    ]),
);

print_r($response);
```

## topUpEsim

This endpoint is used to top-up an existing eSIM with the previously associated destination by providing its ICCID and package details. To determine if an eSIM can be topped up, use the Get eSIM endpoint, which returns the `isTopUpAllowed` flag.

- HTTP Method: `POST`
- Endpoint: `/purchases/topup`

**Parameters**

| Name           | Type     | Required | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |
| :------------- | :------- | :------- | :----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| iccid          | string   | ✅        | ID of the eSIM                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |
| dataLimitInGb  | float    | ✅        | Size of the package in GB. The available options are 0.5, 1, 2, 3, 5, 8, 20, 50GB. Use `-1` to top up with an unlimited (date-based) package — provide `startDate`/`endDate` spanning 3 to 30 days (`duration` is not supported for unlimited packages).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| startDate      | DateTime | ❌        | Start date of the package's validity in the format 'yyyy-MM-dd'. This date can be set to the current day or any day within the next 12 months.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |
| endDate        | DateTime | ❌        | End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| duration       | float    | ❌        | Duration of the package in days. Available values are 1, 2, 7, 14, 30, or 90. Either provide startDate/endDate or duration. Not supported for unlimited packages (`dataLimitInGB` = -1), which are date-based — provide startDate/endDate instead.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| email          | string   | ❌        | Email address where the purchase confirmation email will be sent (excluding QR Code & activation steps).                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| referenceId    | string   | ❌        | An identifier provided by the partner to link this purchase to their booking or transaction for analytics and debugging purposes. Letters, digits, `-`, `_`, `.`, `@` and `+` are allowed (so an email address works), up to 255 characters.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
| emailBrand     | string   | ❌        | Customize the email subject brand. The `emailBrand` parameter cannot exceed 25 characters in length and must contain only letters, numbers, and spaces. This feature is available to platforms with Diamond tier only.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       |
| startTime      | float    | ❌        | Epoch value representing the start time of the package's validity. This timestamp can be set to the current time or any time within the next 12 months.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      |
| endTime        | float    | ❌        | Epoch value representing the end time of the package's validity. End time can be maximum 90 days after Start time.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| idempotencyKey | string   | ❌        | Optional. A unique value (for example a UUID) that makes this request safe to retry. If a request with the same `Idempotency-Key` and the same body succeeds, repeating it returns the original response instead of performing the action again, and the response includes the header `Idempotent-Replayed: true`. Keys are scoped to your credentials and remembered for 3 days. Only successful (2xx) responses are remembered, so a request that failed can be retried with the same key. Reusing a key with a different request returns `422`; sending it while the original request is still being processed returns `409`. If the idempotency store is temporarily unavailable, the request is not processed and returns `503`; retry it. Must be 1 to 255 printable ASCII characters, without spaces. |

**Return Type**

[`TopUpEsimResponse`](../models/TopUpEsimResponse.md)

**Example Usage Code Snippet**

```php
<?php

use Celitech\Client;
use Celitech\Purchases\Requests\TopUpEsimRequest;

$client = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $client->purchases->topUpEsim(
    new TopUpEsimRequest([
        'idempotencyKey' => '8f14e45f-ceea-4672-a1d9-0a5a3c2f1e17',
        'iccid' => '1111222233334444555000',
        'dataLimitInGb' => 1,
    ]),
);

print_r($response);
```

## editPurchase

This endpoint allows you to modify the validity dates of an existing purchase.  

**Behavior:**
- If the purchase has **not yet been activated**, both the start and end dates can be updated.  
- If the purchase is **already active**, only the **end date** can be updated, while the **start date must remain unchanged** (and should be passed as originally set).  
- Updates must comply with the same pricing structure; the modification cannot alter the package size or change its duration category.  

The end date can be extended or shortened as long as it adheres to the same pricing category and does not exceed the allowed duration limits.

- HTTP Method: `POST`
- Endpoint: `/purchases/edit`

**Parameters**

| Name           | Type     | Required | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  |
| :------------- | :------- | :------- | :----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| purchaseId     | string   | ✅        | ID of the purchase                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| startDate      | DateTime | ✅        | Start date of the package's validity in the format 'yyyy-MM-dd'. This date can be set to the current day or any day within the next 12 months.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |
| endDate        | DateTime | ✅        | End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| startTime      | float    | ❌        | Epoch value representing the start time of the package's validity. This timestamp can be set to the current time or any time within the next 12 months.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      |
| endTime        | float    | ❌        | Epoch value representing the end time of the package's validity. End time can be maximum 90 days after Start time.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           |
| idempotencyKey | string   | ❌        | Optional. A unique value (for example a UUID) that makes this request safe to retry. If a request with the same `Idempotency-Key` and the same body succeeds, repeating it returns the original response instead of performing the action again, and the response includes the header `Idempotent-Replayed: true`. Keys are scoped to your credentials and remembered for 3 days. Only successful (2xx) responses are remembered, so a request that failed can be retried with the same key. Reusing a key with a different request returns `422`; sending it while the original request is still being processed returns `409`. If the idempotency store is temporarily unavailable, the request is not processed and returns `503`; retry it. Must be 1 to 255 printable ASCII characters, without spaces. |

**Return Type**

[`EditPurchaseResponse`](../models/EditPurchaseResponse.md)

**Example Usage Code Snippet**

```php
<?php

use Celitech\Client;
use Celitech\Purchases\Requests\EditPurchaseRequest;

$client = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $client->purchases->editPurchase(
    new EditPurchaseRequest([
        'idempotencyKey' => '8f14e45f-ceea-4672-a1d9-0a5a3c2f1e17',
        'purchaseId' => 'ae471106-c8b4-42cf-b83a-b061291f2922',
        'startDate' => new DateTime('2023-11-01'),
        'endDate' => new DateTime('2023-11-20'),
    ]),
);

print_r($response);
```

## getPurchaseConsumption

This endpoint can be called for consumption notifications (e.g. every 1 hour or when the user clicks a button). It returns the data balance (consumption) of purchased packages.

- HTTP Method: `GET`
- Endpoint: `/purchases/{purchaseId}/consumption`

**Parameters**

| Name        | Type   | Required | Description        |
| :---------- | :----- | :------- | :----------------- |
| $purchaseId | string | ✅        | ID of the purchase |

**Return Type**

[`GetPurchaseConsumptionResponse`](../models/GetPurchaseConsumptionResponse.md)

**Example Usage Code Snippet**

```php
<?php

use Celitech\Client;

$client = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $client->purchases->getPurchaseConsumption(
    '4973fa15-6979-4daa-9cf3-672620df819c',
);

print_r($response);
```
