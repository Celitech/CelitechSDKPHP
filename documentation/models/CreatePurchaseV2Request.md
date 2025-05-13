# CreatePurchaseV2Request



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | destination | string | ✅ | ISO representation of the package's destination. |
    | dataLimitInGb | number | ✅ | Size of the package in GB. For **limited packages**, the available options are: **0.5, 1, 2, 3, 5, 8, 20GB** (supports `duration` or `startDate` / `endDate`). For **unlimited packages** (available to Region-3), please use **-1** as an identifier (supports `duration` only).  |
    | quantity | number | ✅ | Number of eSIMs to purchase. |
    | startDate | string | ❌ | Start date of the package's validity in the format 'yyyy-MM-dd'. This date can be set to the current day or any day within the next 12 months. |
    | endDate | string | ❌ | End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date. |
    | duration | number | ❌ | It designates the number of days the eSIM is valid for within the 90-day validity period from the issuance date.   For **limited packages**, the supported durations are: **1, 2, 7, 14, 30, 90 days**.   For **unlimited packages**, all durations are supported **except 90 days**.  |
    | email | string | ❌ | Email address where the purchase confirmation email will be sent (including QR Code & activation steps) |
    | referenceId | string | ❌ | An identifier provided by the partner to link this purchase to their booking or transaction for analytics and debugging purposes. |
    | networkBrand | string | ❌ | Customize the network brand of the issued eSIM. The `networkBrand` parameter cannot exceed 15 characters in length and must contain only letters and numbers. This feature is available to platforms with Diamond tier only. |
    | emailBrand | string | ❌ | Customize the email subject brand. The `emailBrand` parameter cannot exceed 25 characters in length and must contain only letters, numbers, and spaces. This feature is available to platforms with Diamond tier only. |


