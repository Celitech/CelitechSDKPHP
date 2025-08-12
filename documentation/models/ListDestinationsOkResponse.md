# ListDestinationsOkResponse



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | destinations | array | ❌ |  |

# Destinations



**Properties**

| Name | Type | Required | Description |
| :-------- | :----------| :----------| :----------|
    | name | string | ❌ | Name of the destination |
    | destination | string | ❌ | ISO3 representation of the destination |
    | destinationIso2 | string | ❌ | ISO2 representation of the destination |
    | supportedCountries | array | ❌ | This array indicates the geographical area covered by a specific destination. If the destination represents a single country, the array will include that country. However, if the destination represents a broader regional scope, the array will be populated with the names of the countries belonging to that region. |



