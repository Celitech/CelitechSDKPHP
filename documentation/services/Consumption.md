# Consumption

A list of all methods in the `Consumption` service. Click on the method name to view detailed information about that method.

| Methods | Description |
| :------ | :---------- |
|[Get_Purchase_Consumption](#get_purchase_consumption)| This endpoint can be called for consumption notifications (e.g. every 1 hour or when the user clicks a button). It returns the data balance (consumption) of purchased packages. |

## Get_Purchase_Consumption

This endpoint can be called for consumption notifications (e.g. every 1 hour or when the user clicks a button). It returns the data balance (consumption) of purchased packages.


- HTTP Method: `GET`
- Endpoint: `/purchases/{purchaseId}/consumption`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| $purchaseId | string | ✅ |  |
| $accept | string | ✅ |  |

**Return Type**

`mixed`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $sdk->consumption->getPurchaseConsumption(
  accept: "application/json",
  purchaseId: "purchaseId"
);

print_r($response);
```


