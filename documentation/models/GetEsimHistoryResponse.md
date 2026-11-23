# GetEsimHistoryResponse

**Properties**

| Name | Type                                                      | Required | Description |
| :--- | :-------------------------------------------------------- | :------- | :---------- |
| esim | [GetEsimHistoryResponseEsim](#getesimhistoryresponseesim) | ✅        |             |

# GetEsimHistoryResponseEsim

**Properties**

| Name    | Type                                                                              | Required | Description    |
| :------ | :-------------------------------------------------------------------------------- | :------- | :------------- |
| iccid   | string                                                                            | ✅        | ID of the eSIM |
| history | [GetEsimHistoryResponseEsimHistoryItem[]](#getesimhistoryresponseesimhistoryitem) | ✅        |                |

# GetEsimHistoryResponseEsimHistoryItem

**Properties**

| Name       | Type     | Required | Description                                                                                                                         |
| :--------- | :------- | :------- | :---------------------------------------------------------------------------------------------------------------------------------- |
| status     | string   | ✅        | The status of the eSIM at a given time, possible values are 'RELEASED', 'DOWNLOADED', 'INSTALLED', 'ENABLED', 'DELETED', or 'ERROR' |
| statusDate | DateTime | ✅        | The date when the eSIM status changed in the format 'yyyy-MM-ddThh:mm:ssZZ'                                                         |
| date       | float    | ❌        | Epoch value representing the date when the eSIM status changed                                                                      |
