# Packages



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | id | string | ❌ | ID of the package |
    | destination | string | ❌ | ISO representation of the package's destination |
    | dataLimitInBytes | number | ❌ | Size of the package in bytes. For **limited packages**, this field will return the data limit in bytes. For **unlimited packages**, it will return **-1** as an identifier.  |
    | minDays | number | ❌ | Min number of days for the package |
    | maxDays | number | ❌ | Max number of days for the package |
    | priceInCents | number | ❌ | Price of the package in cents |


