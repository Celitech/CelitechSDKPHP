# Celitech PHP SDK
Version: 1.1.0
Welcome to the CELITECH API documentation!

Useful links: [Homepage](https://www.celitech.com) | [Support email](mailto:support@celitech.com) | [Blog](https://www.celitech.com/blog/)


## Install

```bash
composer install celitech-sdk/sdk
```

## Example

```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');

$response = $sdk->Destinations->listDestinations();

print_r($response);

```

## Services
### Destinations
#### listDestinations
List Destinations
```PHP
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');

$response = $sdk->Destinations->listDestinations();

print_r($response);
```
### Packages
#### listPackages
List Packages
```PHP
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');

$response = $sdk->Packages->listPackages();

print_r($response);
```
### Purchases
#### listPurchases
List Purchases
```PHP
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');

$response = $sdk->Purchases->listPurchases();

print_r($response);
```
#### createPurchase
Create Purchase
```PHP
<?php

use Celitech\Client;
use Celitech\Models\CreatePurchaseRequest;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');


$input = new Models\CreatePurchaseRequest(
  destination: "destination",
  dataLimitInGb: 123,
  startDate: "startDate",
  endDate: "endDate",
  email: "email",
  referenceId: "referenceId",
  networkBrand: "networkBrand",
  startTime: 123,
  endTime: 123
);

$response = $sdk->Purchases->createPurchase(
  input: $input
);

print_r($response);
```
#### topUpEsim
Top-up eSIM
```PHP
<?php

use Celitech\Client;
use Celitech\Models\TopUpEsimRequest;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');


$input = new Models\TopUpEsimRequest(
  iccid: "iccid",
  dataLimitInGb: 123,
  startDate: "startDate",
  endDate: "endDate",
  email: "email",
  referenceId: "referenceId",
  startTime: 123,
  endTime: 123
);

$response = $sdk->Purchases->topUpEsim(
  input: $input
);

print_r($response);
```
#### editPurchase
Edit Purchase
```PHP
<?php

use Celitech\Client;
use Celitech\Models\EditPurchaseRequest;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');


$input = new Models\EditPurchaseRequest(
  purchaseId: "purchaseId",
  startDate: "startDate",
  endDate: "endDate",
  startTime: 123,
  endTime: 123
);

$response = $sdk->Purchases->editPurchase(
  input: $input
);

print_r($response);
```
#### getPurchaseConsumption
Get Purchase Consumption
```PHP
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');

$response = $sdk->Purchases->getPurchaseConsumption(
  purchaseId: "purchaseId"
);

print_r($response);
```
### ESim
#### getEsim
Get eSIM Status
```PHP
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');

$response = $sdk->ESim->getEsim(
  iccid: "iccid"
);

print_r($response);
```
#### getEsimDevice
Get eSIM Device
```PHP
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');

$response = $sdk->ESim->getEsimDevice(
  iccid: "iccid"
);

print_r($response);
```
#### getEsimHistory
Get eSIM History
```PHP
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');

$response = $sdk->ESim->getEsimHistory(
  iccid: "iccid"
);

print_r($response);
```
#### getEsimMac
Get eSIM MAC
```PHP
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');

$response = $sdk->ESim->getEsimMac(
  iccid: "iccid"
);

print_r($response);
```
