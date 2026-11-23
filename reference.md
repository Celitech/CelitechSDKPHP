# Reference
## Destinations
<details><summary><code>$client-&gt;destinations-&gt;listDestinations() -> ?ListDestinationsResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

List Destinations
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->destinations->listDestinations();
```
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

## Packages
<details><summary><code>$client-&gt;packages-&gt;listPackages($request) -> ?ListPackagesResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

List Packages
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->packages->listPackages(
    new ListPackagesRequest([
        'destination' => 'FRA',
        'dataLimitInGb' => 1,
        'startDate' => new DateTime('2023-11-01'),
        'endDate' => new DateTime('2023-11-20'),
        'afterCursor' => 'Y3JlYXRlZEF0OjE1OTk0OTMwOTgsZGVzdGluYXRpb246QVVTLG1pbkRheXM6MCxkYXRhTGltaXRJbkJ5dGVzOjUzNjg3MDkxMjA',
        'limit' => 20,
        'includeUnlimited' => true,
    ]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$destination:** `?string` — ISO representation of the package's destination. Supports both ISO2 (e.g., 'FR') and ISO3 (e.g., 'FRA') country codes.
    
</dd>
</dl>

<dl>
<dd>

**$dataLimitInGb:** `?float` — Filter packages by data limit in GB. When provided, only packages with this exact data limit are returned. Use `-1` together with `includeUnlimited=true` to return only unlimited packages. A value of `0` is ignored.
    
</dd>
</dl>

<dl>
<dd>

**$startDate:** `?string` — Start date of the package's validity in the format 'yyyy-MM-dd'. This date can be set to the current day or any day within the next 12 months.
    
</dd>
</dl>

<dl>
<dd>

**$endDate:** `?string` — End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date.
    
</dd>
</dl>

<dl>
<dd>

**$afterCursor:** `?string` — To get the next batch of results, use this parameter. It tells the API where to start fetching data after the last item you received. It helps you avoid repeats and efficiently browse through large sets of data.
    
</dd>
</dl>

<dl>
<dd>

**$limit:** `?float` — Maximum number of packages to be returned in the response. The value must be greater than 0 and less than or equal to 160. If not provided, the default value is 20
    
</dd>
</dl>

<dl>
<dd>

**$startTime:** `?int` — Epoch value representing the start time of the package's validity. This timestamp can be set to the current time or any time within the next 12 months
    
</dd>
</dl>

<dl>
<dd>

**$endTime:** `?int` — Epoch value representing the end time of the package's validity. End time can be maximum 90 days after Start time
    
</dd>
</dl>

<dl>
<dd>

**$includeUnlimited:** `?bool` — Whether to include unlimited (date-based) packages in the results. Unlimited packages are excluded by default; set this to `true` to include them. An unlimited package has `dataLimitInGB` and `dataLimitInBytes` equal to `-1`, and is offered for 3 to 30 days with `minDays` equal to `maxDays`.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

## Purchases
<details><summary><code>$client-&gt;purchases-&gt;createPurchaseV2($request) -> ?array</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

This endpoint is used to purchase a new eSIM by providing the package details.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->purchases->createPurchaseV2(
    new CreatePurchaseV2Request([
        'idempotencyKey' => '8f14e45f-ceea-4672-a1d9-0a5a3c2f1e17',
        'destination' => 'FRA',
        'dataLimitInGb' => 1,
        'quantity' => 1,
    ]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$idempotencyKey:** `?string` — Optional. A unique value (for example a UUID) that makes this request safe to retry. If a request with the same `Idempotency-Key` and the same body succeeds, repeating it returns the original response instead of performing the action again, and the response includes the header `Idempotent-Replayed: true`. Keys are scoped to your credentials and remembered for 3 days. Only successful (2xx) responses are remembered, so a request that failed can be retried with the same key. Reusing a key with a different request returns `422`; sending it while the original request is still being processed returns `409`. If the idempotency store is temporarily unavailable, the request is not processed and returns `503`; retry it. Must be 1 to 255 printable ASCII characters, without spaces.
    
</dd>
</dl>

<dl>
<dd>

**$destination:** `string` — ISO representation of the package's destination. Supports both ISO2 (e.g., 'FR') and ISO3 (e.g., 'FRA') country codes.
    
</dd>
</dl>

<dl>
<dd>

**$dataLimitInGb:** `float` — Size of the package in GB. The available options are 0.5, 1, 2, 3, 5, 8, 20, 50GB. Use `-1` to purchase an unlimited (date-based) package — provide `startDate`/`endDate` spanning 3 to 30 days (`duration` is not supported for unlimited packages).
    
</dd>
</dl>

<dl>
<dd>

**$startDate:** `?string` — Start date of the package's validity in the format 'yyyy-MM-dd'. This date can be set to the current day or any day within the next 12 months.
    
</dd>
</dl>

<dl>
<dd>

**$endDate:** `?string` — End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date.
    
</dd>
</dl>

<dl>
<dd>

**$duration:** `?float` — Duration of the package in days. Available values are 1, 2, 7, 14, 30, or 90. Either provide startDate/endDate or duration. Not supported for unlimited packages (`dataLimitInGB` = -1), which are date-based — provide startDate/endDate instead.
    
</dd>
</dl>

<dl>
<dd>

**$quantity:** `float` — Number of eSIMs to purchase.
    
</dd>
</dl>

<dl>
<dd>

**$email:** `?string` — Email address where the purchase confirmation email will be sent (including QR Code & activation steps)
    
</dd>
</dl>

<dl>
<dd>

**$referenceId:** `?string` — An identifier provided by the partner to link this purchase to their booking or transaction for analytics and debugging purposes. Letters, digits, `-`, `_`, `.`, `@` and `+` are allowed (so an email address works), up to 255 characters.
    
</dd>
</dl>

<dl>
<dd>

**$networkBrand:** `?string` — Customize the network brand of the issued eSIM. The `networkBrand` parameter cannot exceed 15 characters in length and must contain only letters, numbers, dots (.), ampersands (&), and spaces. This feature is available to platforms with Diamond tier only.
    
</dd>
</dl>

<dl>
<dd>

**$emailBrand:** `?string` — Customize the email subject brand. The `emailBrand` parameter cannot exceed 25 characters in length and must contain only letters, numbers, and spaces. This feature is available to platforms with Diamond tier only.
    
</dd>
</dl>

<dl>
<dd>

**$language:** `?CreatePurchaseV2RequestLanguage` — Language of the confirmation email sent to the customer.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;purchases-&gt;listPurchases($request) -> ?ListPurchasesResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

This endpoint can be used to list all the successful purchases made between a given interval.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->purchases->listPurchases(
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
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$purchaseId:** `?string` — ID of the purchase
    
</dd>
</dl>

<dl>
<dd>

**$iccid:** `?string` — ID of the eSIM
    
</dd>
</dl>

<dl>
<dd>

**$afterDate:** `?string` — Start date of the interval for filtering purchases in the format 'yyyy-MM-dd'
    
</dd>
</dl>

<dl>
<dd>

**$beforeDate:** `?string` — End date of the interval for filtering purchases in the format 'yyyy-MM-dd'
    
</dd>
</dl>

<dl>
<dd>

**$email:** `?string` — Email associated to the purchase.
    
</dd>
</dl>

<dl>
<dd>

**$referenceId:** `?string` — The referenceId that was provided by the partner during the purchase or topup flow.
    
</dd>
</dl>

<dl>
<dd>

**$afterCursor:** `?string` — To get the next batch of results, use this parameter. It tells the API where to start fetching data after the last item you received. It helps you avoid repeats and efficiently browse through large sets of data.
    
</dd>
</dl>

<dl>
<dd>

**$limit:** `?float` — Maximum number of purchases to be returned in the response. The value must be greater than 0 and less than or equal to 100. If not provided, the default value is 20
    
</dd>
</dl>

<dl>
<dd>

**$after:** `?float` — Epoch value representing the start of the time interval for filtering purchases
    
</dd>
</dl>

<dl>
<dd>

**$before:** `?float` — Epoch value representing the end of the time interval for filtering purchases
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;purchases-&gt;createPurchase($request) -> ?CreatePurchaseResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

This endpoint is used to purchase a new eSIM by providing the package details.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->purchases->createPurchase(
    new CreatePurchaseRequest([
        'idempotencyKey' => '8f14e45f-ceea-4672-a1d9-0a5a3c2f1e17',
        'destination' => 'FRA',
        'dataLimitInGb' => 1,
        'startDate' => new DateTime('2023-11-01'),
        'endDate' => new DateTime('2023-11-20'),
    ]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$idempotencyKey:** `?string` — Optional. A unique value (for example a UUID) that makes this request safe to retry. If a request with the same `Idempotency-Key` and the same body succeeds, repeating it returns the original response instead of performing the action again, and the response includes the header `Idempotent-Replayed: true`. Keys are scoped to your credentials and remembered for 3 days. Only successful (2xx) responses are remembered, so a request that failed can be retried with the same key. Reusing a key with a different request returns `422`; sending it while the original request is still being processed returns `409`. If the idempotency store is temporarily unavailable, the request is not processed and returns `503`; retry it. Must be 1 to 255 printable ASCII characters, without spaces.
    
</dd>
</dl>

<dl>
<dd>

**$destination:** `string` — ISO representation of the package's destination. Supports both ISO2 (e.g., 'FR') and ISO3 (e.g., 'FRA') country codes.
    
</dd>
</dl>

<dl>
<dd>

**$dataLimitInGb:** `float` — Size of the package in GB. The available options are 0.5, 1, 2, 3, 5, 8, 20, 50GB. Use `-1` to purchase an unlimited (date-based) package — provide `startDate`/`endDate` spanning 3 to 30 days.
    
</dd>
</dl>

<dl>
<dd>

**$startDate:** `string` — Start date of the package's validity in the format 'yyyy-MM-dd'. This date can be set to the current day or any day within the next 12 months.
    
</dd>
</dl>

<dl>
<dd>

**$endDate:** `string` — End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date.
    
</dd>
</dl>

<dl>
<dd>

**$email:** `?string` — Email address where the purchase confirmation email will be sent (including QR Code & activation steps)
    
</dd>
</dl>

<dl>
<dd>

**$referenceId:** `?string` — An identifier provided by the partner to link this purchase to their booking or transaction for analytics and debugging purposes. Letters, digits, `-`, `_`, `.`, `@` and `+` are allowed (so an email address works), up to 255 characters.
    
</dd>
</dl>

<dl>
<dd>

**$networkBrand:** `?string` — Customize the network brand of the issued eSIM. The `networkBrand` parameter cannot exceed 15 characters in length and must contain only letters, numbers, dots (.), ampersands (&), and spaces. This feature is available to platforms with Diamond tier only.
    
</dd>
</dl>

<dl>
<dd>

**$emailBrand:** `?string` — Customize the email subject brand. The `emailBrand` parameter cannot exceed 25 characters in length and must contain only letters, numbers, and spaces. This feature is available to platforms with Diamond tier only.
    
</dd>
</dl>

<dl>
<dd>

**$language:** `?CreatePurchaseRequestLanguage` — Language of the confirmation email sent to the customer.
    
</dd>
</dl>

<dl>
<dd>

**$startTime:** `?float` — Epoch value representing the start time of the package's validity. This timestamp can be set to the current time or any time within the next 12 months.
    
</dd>
</dl>

<dl>
<dd>

**$endTime:** `?float` — Epoch value representing the end time of the package's validity. End time can be maximum 90 days after Start time.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;purchases-&gt;topUpEsim($request) -> ?TopUpEsimResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

This endpoint is used to top-up an existing eSIM with the previously associated destination by providing its ICCID and package details. To determine if an eSIM can be topped up, use the Get eSIM endpoint, which returns the `isTopUpAllowed` flag.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->purchases->topUpEsim(
    new TopUpEsimRequest([
        'idempotencyKey' => '8f14e45f-ceea-4672-a1d9-0a5a3c2f1e17',
        'iccid' => '1111222233334444555000',
        'dataLimitInGb' => 1,
    ]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$idempotencyKey:** `?string` — Optional. A unique value (for example a UUID) that makes this request safe to retry. If a request with the same `Idempotency-Key` and the same body succeeds, repeating it returns the original response instead of performing the action again, and the response includes the header `Idempotent-Replayed: true`. Keys are scoped to your credentials and remembered for 3 days. Only successful (2xx) responses are remembered, so a request that failed can be retried with the same key. Reusing a key with a different request returns `422`; sending it while the original request is still being processed returns `409`. If the idempotency store is temporarily unavailable, the request is not processed and returns `503`; retry it. Must be 1 to 255 printable ASCII characters, without spaces.
    
</dd>
</dl>

<dl>
<dd>

**$iccid:** `string` — ID of the eSIM
    
</dd>
</dl>

<dl>
<dd>

**$dataLimitInGb:** `float` — Size of the package in GB. The available options are 0.5, 1, 2, 3, 5, 8, 20, 50GB. Use `-1` to top up with an unlimited (date-based) package — provide `startDate`/`endDate` spanning 3 to 30 days (`duration` is not supported for unlimited packages).
    
</dd>
</dl>

<dl>
<dd>

**$startDate:** `?string` — Start date of the package's validity in the format 'yyyy-MM-dd'. This date can be set to the current day or any day within the next 12 months.
    
</dd>
</dl>

<dl>
<dd>

**$endDate:** `?string` — End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date.
    
</dd>
</dl>

<dl>
<dd>

**$duration:** `?float` — Duration of the package in days. Available values are 1, 2, 7, 14, 30, or 90. Either provide startDate/endDate or duration. Not supported for unlimited packages (`dataLimitInGB` = -1), which are date-based — provide startDate/endDate instead.
    
</dd>
</dl>

<dl>
<dd>

**$email:** `?string` — Email address where the purchase confirmation email will be sent (excluding QR Code & activation steps).
    
</dd>
</dl>

<dl>
<dd>

**$referenceId:** `?string` — An identifier provided by the partner to link this purchase to their booking or transaction for analytics and debugging purposes. Letters, digits, `-`, `_`, `.`, `@` and `+` are allowed (so an email address works), up to 255 characters.
    
</dd>
</dl>

<dl>
<dd>

**$emailBrand:** `?string` — Customize the email subject brand. The `emailBrand` parameter cannot exceed 25 characters in length and must contain only letters, numbers, and spaces. This feature is available to platforms with Diamond tier only.
    
</dd>
</dl>

<dl>
<dd>

**$startTime:** `?float` — Epoch value representing the start time of the package's validity. This timestamp can be set to the current time or any time within the next 12 months.
    
</dd>
</dl>

<dl>
<dd>

**$endTime:** `?float` — Epoch value representing the end time of the package's validity. End time can be maximum 90 days after Start time.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;purchases-&gt;editPurchase($request) -> ?EditPurchaseResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

This endpoint allows you to modify the validity dates of an existing purchase.  

**Behavior:**
- If the purchase has **not yet been activated**, both the start and end dates can be updated.  
- If the purchase is **already active**, only the **end date** can be updated, while the **start date must remain unchanged** (and should be passed as originally set).  
- Updates must comply with the same pricing structure; the modification cannot alter the package size or change its duration category.  

The end date can be extended or shortened as long as it adheres to the same pricing category and does not exceed the allowed duration limits.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->purchases->editPurchase(
    new EditPurchaseRequest([
        'idempotencyKey' => '8f14e45f-ceea-4672-a1d9-0a5a3c2f1e17',
        'purchaseId' => 'ae471106-c8b4-42cf-b83a-b061291f2922',
        'startDate' => new DateTime('2023-11-01'),
        'endDate' => new DateTime('2023-11-20'),
    ]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$idempotencyKey:** `?string` — Optional. A unique value (for example a UUID) that makes this request safe to retry. If a request with the same `Idempotency-Key` and the same body succeeds, repeating it returns the original response instead of performing the action again, and the response includes the header `Idempotent-Replayed: true`. Keys are scoped to your credentials and remembered for 3 days. Only successful (2xx) responses are remembered, so a request that failed can be retried with the same key. Reusing a key with a different request returns `422`; sending it while the original request is still being processed returns `409`. If the idempotency store is temporarily unavailable, the request is not processed and returns `503`; retry it. Must be 1 to 255 printable ASCII characters, without spaces.
    
</dd>
</dl>

<dl>
<dd>

**$purchaseId:** `string` — ID of the purchase
    
</dd>
</dl>

<dl>
<dd>

**$startDate:** `string` — Start date of the package's validity in the format 'yyyy-MM-dd'. This date can be set to the current day or any day within the next 12 months.
    
</dd>
</dl>

<dl>
<dd>

**$endDate:** `string` — End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date.
    
</dd>
</dl>

<dl>
<dd>

**$startTime:** `?float` — Epoch value representing the start time of the package's validity. This timestamp can be set to the current time or any time within the next 12 months.
    
</dd>
</dl>

<dl>
<dd>

**$endTime:** `?float` — Epoch value representing the end time of the package's validity. End time can be maximum 90 days after Start time.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;purchases-&gt;getPurchaseConsumption($purchaseId) -> ?GetPurchaseConsumptionResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

This endpoint can be called for consumption notifications (e.g. every 1 hour or when the user clicks a button). It returns the data balance (consumption) of purchased packages.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->purchases->getPurchaseConsumption(
    '4973fa15-6979-4daa-9cf3-672620df819c',
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$purchaseId:** `string` — ID of the purchase
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

## ESim
<details><summary><code>$client-&gt;eSim-&gt;getEsim($request) -> ?GetEsimResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Get eSIM
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->eSim->getEsim(
    new GetEsimRequest([
        'iccid' => '1111222233334444555000',
    ]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$iccid:** `string` — ID of the eSIM
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;eSim-&gt;getEsimDevice($iccid) -> ?GetEsimDeviceResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Get eSIM Device
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->eSim->getEsimDevice(
    '1111222233334444555000',
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$iccid:** `string` — ID of the eSIM
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;eSim-&gt;getEsimHistory($iccid) -> ?GetEsimHistoryResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Get eSIM History
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->eSim->getEsimHistory(
    '1111222233334444555000',
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$iccid:** `string` — ID of the eSIM
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;eSim-&gt;suspendEsim($iccid, $request) -> ?SuspendEsimResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Suspends an eSIM to prevent abuse. All of the eSIM's packages are terminated and its connectivity is switched off. The eSIM is not refunded: the charge stays.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->eSim->suspendEsim(
    '1111222233334444555000',
    new SuspendEsimRequest([
        'idempotencyKey' => '8f14e45f-ceea-4672-a1d9-0a5a3c2f1e17',
    ]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$iccid:** `string` — ID of the eSIM
    
</dd>
</dl>

<dl>
<dd>

**$idempotencyKey:** `?string` — Optional. A unique value (for example a UUID) that makes this request safe to retry. If a request with the same `Idempotency-Key` and the same body succeeds, repeating it returns the original response instead of performing the action again, and the response includes the header `Idempotent-Replayed: true`. Keys are scoped to your credentials and remembered for 3 days. Only successful (2xx) responses are remembered, so a request that failed can be retried with the same key. Reusing a key with a different request returns `422`; sending it while the original request is still being processed returns `409`. If the idempotency store is temporarily unavailable, the request is not processed and returns `503`; retry it. Must be 1 to 255 printable ASCII characters, without spaces.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

## IFrame
<details><summary><code>$client-&gt;iFrame-&gt;token() -> ?TokenResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Generate a new token to be used in the iFrame
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->iFrame->token();
```
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

## OAuth
<details><summary><code>$client-&gt;oAuthClient-&gt;getAccessToken($request) -> ?OAuthTokenResponse</code></summary>
<dl>
<dd>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$grantType:** `GrantType` 
    
</dd>
</dl>

<dl>
<dd>

**$clientId:** `string` 
    
</dd>
</dl>

<dl>
<dd>

**$clientSecret:** `string` 
    
</dd>
</dl>

<dl>
<dd>

**$scope:** `string` 
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

