# ListPurchasesOkResponse



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | purchases | [Purchases](Purchases.md)[] | ✅ |  |
    | afterCursor | string | ✅ | The cursor value representing the end of the current page of results. Use this cursor value as the "afterCursor" parameter in your next request to retrieve the subsequent page of results. It ensures that you continue fetching data from where you left off, facilitating smooth pagination. |

# Purchases



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | id | string | ✅ | ID of the purchase |
    | startDate | string | ✅ | Start date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ' |
    | endDate | string | ✅ | End date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ' |
    | createdDate | string | ✅ | Creation date of the purchase in the format 'yyyy-MM-ddThh:mm:ssZZ' |
    | package | Package | ✅ |  |
    | esim | PurchasesEsim | ✅ |  |
    | source | string | ✅ | The `source` indicates whether the purchase was made from the API, dashboard, landing-page, promo-page or iframe. For purchases made before September 8, 2023, the value will be displayed as 'Not available'. |
    | purchaseType | string | ✅ | The `purchaseType` indicates whether this is the initial purchase that creates the eSIM (First Purchase) or a subsequent top-up on an existing eSIM (Top-up Purchase). |
    | duration | float | ❌ | Duration of the package in days. Possible values are 1, 2, 7, 14, 30, or 90. |
    | startTime | float | ❌ | Epoch value representing the start time of the package's validity |
    | endTime | float | ❌ | Epoch value representing the end time of the package's validity |
    | createdAt | float | ❌ | Epoch value representing the date of creation of the purchase |
    | referenceId | string | ❌ | The `referenceId` that was provided by the partner during the purchase or top-up flow. This identifier can be used for analytics and debugging purposes. |

# Package



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | id | string | ✅ | ID of the package |
    | dataLimitInBytes | float | ✅ | Size of the package in Bytes |
    | dataLimitInGB | float | ✅ | Size of the package in GB |
    | destination | string | ✅ | ISO3 representation of the package's destination. |
    | destinationISO2 | string | ✅ | ISO2 representation of the package's destination. |
    | destinationName | string | ✅ | Name of the package's destination |
    | priceInCents | float | ✅ | Price of the package in cents |


# PurchasesEsim



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | iccid | string | ✅ | ID of the eSIM |




