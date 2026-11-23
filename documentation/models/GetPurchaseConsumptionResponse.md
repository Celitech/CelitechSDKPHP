# GetPurchaseConsumptionResponse

**Properties**

| Name                      | Type   | Required | Description                                                                     |
| :------------------------ | :----- | :------- | :------------------------------------------------------------------------------ |
| dataUsageRemainingInBytes | float  | ✅        | Remaining balance of the package in bytes. Returns `-1` for unlimited packages. |
| dataUsageRemainingInGb    | float  | ✅        | Remaining balance of the package in GB. Returns `-1` for unlimited packages.    |
| status                    | string | ✅        | Status of the connectivity, possible values are 'ACTIVE' or 'NOT_ACTIVE'        |
