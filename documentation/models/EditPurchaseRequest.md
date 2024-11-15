# EditPurchaseRequest



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | purchaseId | string | ✅ | ID of the purchase |
    | startDate | string | ✅ | Start date of the package's validity in the format 'yyyy-MM-dd'. This date can be set to the current day or any day within the next 12 months. |
    | endDate | string | ✅ | End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date. |
    | startTime | number | ❌ | Epoch value representing the start time of the package's validity. This timestamp can be set to the current time or any time within the next 12 months. |
    | endTime | number | ❌ | Epoch value representing the end time of the package's validity. End time can be maximum 90 days after Start time. |


