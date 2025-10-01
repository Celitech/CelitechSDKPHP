# GetEsimOkResponseEsim



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | iccid | string | ❌ | ID of the eSIM |
    | smdpAddress | string | ❌ | SM-DP+ Address |
    | manualActivationCode | string | ❌ | The manual activation code |
    | status | string | ❌ | Status of the eSIM, possible values are 'RELEASED', 'DOWNLOADED', 'INSTALLED', 'ENABLED', 'DELETED', or 'ERROR' |
    | isTopUpAllowed | boolean | ❌ | Indicates whether the eSIM is currently eligible for a top-up. This flag should be checked before attempting a top-up request. |


