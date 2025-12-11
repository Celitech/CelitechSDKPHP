# GetEsimOkResponse



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | esim | GetEsimOkResponseEsim | ✅ |  |

# GetEsimOkResponseEsim



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | iccid | string | ✅ | ID of the eSIM |
    | smdpAddress | string | ✅ | SM-DP+ Address |
    | activationCode | string | ✅ | QR Code of the eSIM as base64 |
    | manualActivationCode | string | ✅ | The manual activation code |
    | status | string | ✅ | Status of the eSIM, possible values are 'RELEASED', 'DOWNLOADED', 'INSTALLED', 'ENABLED', 'DELETED', or 'ERROR' |
    | connectivityStatus | string | ✅ | Status of the eSIM connectivity, possible values are 'ACTIVE' or 'NOT_ACTIVE' |
    | isTopUpAllowed | bool | ✅ | Indicates whether the eSIM is currently eligible for a top-up. This flag should be checked before attempting a top-up request. |



