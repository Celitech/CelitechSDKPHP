# ListPurchasesOkResponse



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | purchases | array | ❌ |  |
    | afterCursor | string | ❌ | The cursor value representing the end of the current page of results. Use this cursor value as the "afterCursor" parameter in your next request to retrieve the subsequent page of results. It ensures that you continue fetching data from where you left off, facilitating smooth pagination. |

# Purchases



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | id | string | ❌ | ID of the purchase |
    | startDate | string | ❌ | Start date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ' |
    | endDate | string | ❌ | End date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ' |
    | createdDate | string | ❌ | Creation date of the purchase in the format 'yyyy-MM-ddThh:mm:ssZZ' |
    | startTime | number | ❌ | Epoch value representing the start time of the package's validity |
    | endTime | number | ❌ | Epoch value representing the end time of the package's validity |
    | createdAt | number | ❌ | Epoch value representing the date of creation of the purchase |
    | package | model | ❌ |  |
    | esim | model | ❌ |  |
    | source | string | ❌ | The `source` indicates whether the purchase was made from the API, dashboard, landing-page, promo-page or iframe. For purchases made before September 8, 2023, the value will be displayed as 'Not available'. |
    | purchaseType | string | ❌ | The `purchaseType` indicates whether this is the initial purchase that creates the eSIM (First Purchase) or a subsequent top-up on an existing eSIM (Top-up Purchase). |
    | referenceId | string | ❌ | The `referenceId` that was provided by the partner during the purchase or top-up flow. This identifier can be used for analytics and debugging purposes. |

# Package



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | id | string | ❌ | ID of the package |
    | dataLimitInBytes | number | ❌ | Size of the package in Bytes |
    | destination | string | ❌ | ISO representation of the package's destination. |
    | destinationName | string | ❌ | Name of the package's destination |
    | priceInCents | number | ❌ | Price of the package in cents |


# PurchasesEsim



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | iccid | string | ❌ | ID of the eSIM |




