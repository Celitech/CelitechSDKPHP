# CreatePurchaseV2Request



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | destination | string | ✅ | ISO representation of the package's destination |
    | dataLimitInGb | number | ✅ | Size of the package in GB. - **Limited Packages (1, 2, 3, 5, 8, 20GB):** supports `duration` or `startDate` `endDate` - **Unlimited Packages (Region-3 only)** supports `duration` only. Use **-1** for unlimited.  |
    | quantity | number | ✅ | Number of eSIMs to purchase. |
    | startDate | string | ❌ | Start date of the package validity in the format `yyyy-MM-dd`. This date can be set to the current day or any day within the enxt 12 months.  - **Required** if `duration` is **not** provided.   - **Optional** must not passed if `duration` is provided.  |
    | endDate | string | ❌ | End date of the package validity in the format `yyyy-MM-dd`. End date can be maximum 90 days after Start date.  - **Required** if `duration` is **not** provided.   - **Optional** must not passed if `duration` is provided.  |
    | duration | number | ❌ | Defines the number of days the eSIM package remains active. Available options: **1, 2, 7, 14, 30**  - **Required** if `startDate` and `endDate` are **not** provided.   - **Optional** must not passed if `startDate` and `endDate` are provided.      |
    | email | string | ❌ | Email address where the purchase confirmation email will be sent (including QR Code & activation steps) |
    | referenceId | string | ❌ | An identifier provided by the partner to link this purchase to their booking or transaction for analytics and debugging purposes. |
    | networkBrand | string | ❌ | Customize the network brand of the issued eSIM. This parameter is accessible to platforms with Diamond tier and requires an alphanumeric string of up to 15 characters. |


