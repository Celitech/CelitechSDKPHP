# TopUpEsimRequest



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | iccid | string | ✅ | ID of the eSIM |
    | dataLimitInGB | float | ✅ | Size of the package in GB. The available options are 0.5, 1, 2, 3, 5, 8, 20, 50GB. Use `-1` to top up with an unlimited (date-based) package — provide `startDate`/`endDate` spanning 3 to 30 days (`duration` is not supported for unlimited packages). |
    | startDate | string | ❌ | Start date of the package's validity in the format 'yyyy-MM-dd'. This date can be set to the current day or any day within the next 12 months. |
    | endDate | string | ❌ | End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date. |
    | duration | float | ❌ | Duration of the package in days. Available values are 1, 2, 7, 14, 30, or 90. Either provide startDate/endDate or duration. Not supported for unlimited packages (`dataLimitInGB` = -1), which are date-based — provide startDate/endDate instead. |
    | email | string | ❌ | Email address where the purchase confirmation email will be sent (excluding QR Code & activation steps). |
    | referenceId | string | ❌ | An identifier provided by the partner to link this purchase to their booking or transaction for analytics and debugging purposes. |
    | emailBrand | string | ❌ | Customize the email subject brand. The `emailBrand` parameter cannot exceed 25 characters in length and must contain only letters, numbers, and spaces. This feature is available to platforms with Diamond tier only. |
    | startTime | float | ❌ | Epoch value representing the start time of the package's validity. This timestamp can be set to the current time or any time within the next 12 months. |
    | endTime | float | ❌ | Epoch value representing the end time of the package's validity. End time can be maximum 90 days after Start time. |


