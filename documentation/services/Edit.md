# Edit

A list of all methods in the `Edit` service. Click on the method name to view detailed information about that method.

| Methods | Description |
| :------ | :---------- |
|[Edit_Purchase](#edit_purchase)| This endpoint allows you to modify the validity dates of an existing purchase.   **Behavior:** - If the purchase has **not yet been activated**, both the start and end dates can be updated.   - If the purchase is **already active**, only the **end date** can be updated, while the **start date must remain unchanged** (and should be passed as originally set).   - Updates must comply with the same pricing structure; the modification cannot alter the package size or change its duration category.   The end date can be extended or shortened as long as it adheres to the same pricing category and does not exceed the allowed duration limits.  |

## Edit_Purchase

This endpoint allows you to modify the validity dates of an existing purchase.   **Behavior:** - If the purchase has **not yet been activated**, both the start and end dates can be updated.   - If the purchase is **already active**, only the **end date** can be updated, while the **start date must remain unchanged** (and should be passed as originally set).   - Updates must comply with the same pricing structure; the modification cannot alter the package size or change its duration category.   The end date can be extended or shortened as long as it adheres to the same pricing category and does not exceed the allowed duration limits. 


- HTTP Method: `POST`
- Endpoint: `/purchases/edit`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| input | Models\EditPurchaseRequest | ✅ | This endpoint allows you to modify the validity dates of an existing purchase.   **Behavior:** - If the purchase has **not yet been activated**, both the start and end dates can be updated.   - If the purchase is **already active**, only the **end date** can be updated, while the **start date must remain unchanged** (and should be passed as originally set).   - Updates must comply with the same pricing structure; the modification cannot alter the package size or change its duration category.   The end date can be extended or shortened as long as it adheres to the same pricing category and does not exceed the allowed duration limits.  |
| $accept | string | ✅ |  |

**Return Type**

`mixed`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;
use Celitech\Models\EditPurchaseRequest;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');


$input = new Models\EditPurchaseRequest();

$response = $sdk->edit->editPurchase(
  input: $input,
  accept: "application/json"
);

print_r($response);
```


