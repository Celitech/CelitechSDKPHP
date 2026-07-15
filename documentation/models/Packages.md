# Packages



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | id | string | ✅ | ID of the package |
    | destination | string | ✅ | ISO3 representation of the package's destination. |
    | destinationISO2 | string | ✅ | ISO2 representation of the package's destination. |
    | dataLimitInBytes | float | ✅ | Size of the package in Bytes. A value of `-1` indicates an unlimited package. |
    | dataLimitInGB | float | ✅ | Size of the package in GB. A value of `-1` indicates an unlimited (date-based) package. |
    | minDays | float | ✅ | Min number of days for the package |
    | maxDays | float | ✅ | Max number of days for the package |
    | priceInCents | float | ✅ | Price of the package in cents |


