# ListPackagesOkResponse



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | packages | array | ❌ |  |
    | afterCursor | string | ❌ | The cursor value representing the end of the current page of results. Use this cursor value as the "afterCursor" parameter in your next request to retrieve the subsequent page of results. It ensures that you continue fetching data from where you left off, facilitating smooth pagination |

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



