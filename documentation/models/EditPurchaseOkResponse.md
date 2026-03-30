# EditPurchaseOkResponse



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | purchaseId | string | ✅ | ID of the purchase |
    | newStartDate | string | ✅ | Start date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ' |
    | newEndDate | string | ✅ | End date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ' |
    | newStartTime | float | ❌ | Epoch value representing the new start time of the package's validity |
    | newEndTime | float | ❌ | Epoch value representing the new end time of the package's validity |


