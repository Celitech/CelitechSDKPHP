# TopUpEsimResponse

**Properties**

| Name     | Type                                                    | Required | Description |
| :------- | :------------------------------------------------------ | :------- | :---------- |
| purchase | [TopUpEsimResponsePurchase](#topupesimresponsepurchase) | ✅        |             |
| profile  | [TopUpEsimResponseProfile](#topupesimresponseprofile)   | ✅        |             |

# TopUpEsimResponsePurchase

**Properties**

| Name        | Type     | Required | Description                                                                |
| :---------- | :------- | :------- | :------------------------------------------------------------------------- |
| id          | string   | ✅        | ID of the purchase                                                         |
| packageId   | string   | ✅        | ID of the package                                                          |
| startDate   | DateTime | ✅        | Start date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ' |
| endDate     | DateTime | ✅        | End date of the package's validity in the format 'yyyy-MM-ddThh:mm:ssZZ'   |
| createdDate | DateTime | ✅        | Creation date of the purchase in the format 'yyyy-MM-ddThh:mm:ssZZ'        |
| startTime   | float    | ❌        | Epoch value representing the start time of the package's validity          |
| endTime     | float    | ❌        | Epoch value representing the end time of the package's validity            |

# TopUpEsimResponseProfile

**Properties**

| Name  | Type   | Required | Description    |
| :---- | :----- | :------- | :------------- |
| iccid | string | ✅        | ID of the eSIM |
