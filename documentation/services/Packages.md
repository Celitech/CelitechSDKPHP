# Packages

A list of all methods in the `Packages` service. Click on the method name to view detailed information about that method.

| Methods | Description |
| :------ | :---------- |
|[listPackages](#listpackages)| List Packages |

## listPackages

List Packages


- HTTP Method: `GET`
- Endpoint: `/packages`

**Parameters**

| Name    | Type| Required | Description |
| :-------- | :----------| :----------| :----------|
| $destination | string | ❌ | ISO representation of the package's destination. Supports both ISO2 (e.g., 'FR') and ISO3 (e.g., 'FRA') country codes. |
| $startDate | string | ❌ | Start date of the package's validity in the format 'yyyy-MM-dd'. This date can be set to the current day or any day within the next 12 months. |
| $endDate | string | ❌ | End date of the package's validity in the format 'yyyy-MM-dd'. End date can be maximum 90 days after Start date. |
| $afterCursor | string | ❌ | To get the next batch of results, use this parameter. It tells the API where to start fetching data after the last item you received. It helps you avoid repeats and efficiently browse through large sets of data. |
| $limit | float | ❌ | Maximum number of packages to be returned in the response. The value must be greater than 0 and less than or equal to 160. If not provided, the default value is 20 |
| $startTime | int | ❌ | Epoch value representing the start time of the package's validity. This timestamp can be set to the current time or any time within the next 12 months |
| $endTime | int | ❌ | Epoch value representing the end time of the package's validity. End time can be maximum 90 days after Start time |
| $duration | float | ❌ | Duration in seconds for the package's validity. If this parameter is present, it will override the startTime and endTime parameters. The maximum duration for a package's validity period is 90 days |

**Return Type**

`Models\ListPackagesOkResponse`

**Example Usage Code Snippet**
```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $sdk->packages->listPackages();

print_r($response);
```


