# TopUpEsimOkResponse



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | purchase | model | ❌ |  |
    | profile | model | ❌ |  |

# TopUpEsimOkResponsePurchase



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | id | string | ❌ | ID of the purchase |
    | packageId | string | ❌ | ID of the package |
    | startDate | string | ❌ | Start date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ' |
    | endDate | string | ❌ | End date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ' |
    | duration | number | ❌ | It designates the number of days the eSIM is valid for within 90-day validity from issuance date. |
    | createdDate | string | ❌ | Creation date of the purchase in the format 'yyyy-MM-ddThh:mm:ssZZ' |
    | startTime | number | ❌ | Epoch value representing the start time of the package's validity |
    | endTime | number | ❌ | Epoch value representing the end time of the package's validity |


# TopUpEsimOkResponseProfile



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | iccid | string | ❌ | ID of the eSIM |



