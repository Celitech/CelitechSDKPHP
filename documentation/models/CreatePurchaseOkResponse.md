# CreatePurchaseOkResponse



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | purchase | CreatePurchaseOkResponsePurchase | ✅ |  |
    | profile | CreatePurchaseOkResponseProfile | ✅ |  |

# CreatePurchaseOkResponsePurchase



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | id | string | ✅ | ID of the purchase |
    | packageId | string | ✅ | ID of the package |
    | startDate | string | ✅ | Start date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ' |
    | endDate | string | ✅ | End date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ' |
    | createdDate | string | ✅ | Creation date of the purchase in the format 'yyyy-MM-ddThh:mm:ssZZ' |
    | startTime | float | ❌ | Epoch value representing the start time of the package's validity |
    | endTime | float | ❌ | Epoch value representing the end time of the package's validity |


# CreatePurchaseOkResponseProfile



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | iccid | string | ✅ | ID of the eSIM |
    | activationCode | string | ✅ | QR Code of the eSIM as base64 |
    | manualActivationCode | string | ✅ | Manual Activation Code of the eSIM |



