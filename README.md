# Celitech PHP SDK 1.3.18


Welcome to the Celitech SDK documentation. This guide will help you get started with integrating and using the Celitech SDK in your project.

## Versions

- API version: `1.1.0`
- SDK version: `1.3.18`

## About the API

Welcome to the CELITECH API documentation!  Useful links: [Homepage](https://www.celitech.com) | [Support email](mailto:support@celitech.com) | [Blog](https://www.celitech.com/blog/) 

## Table of Contents
- [Setup & Configuration](#setup--configuration)
	- [Supported Language Versions](#supported-language-versions)
	- [Installation](#installation)
- [Authentication](#authentication)
	- [OAuth Authentication](#oauth-authentication)
  - [Environment Variables](#environment-variables)
- [Setting a Custom Timeout](#setting-a-custom-timeout)
- [Sample Usage](#sample-usage)
- [Services](#services)
- [Models](#models)
- [License](#license)

## Setup & Configuration

### Supported Language Versions

This SDK is compatible with the following versions: `PHP >= 8.0`

### Installation

To get started with the SDK, we recommend installing using `composer`:

```bash
composer require celitech-sdk/sdk
```

## Authentication


### OAuth Authentication

The Celitech API uses OAuth for authentication.

You need to provide the OAuth parameters when initializing the SDK.

```php

```

If you need to set or update the OAuth parameters after the SDK initialization, you can use:

```php

```


## Environment Variables

These are the environment variables for the SDK:

| Name      | Description  |
| :-------- | :----------- |
| CLIENT_ID | Client ID parameter |
| CLIENT_SECRET | Client Secret parameter |

Environment variables are a way to configure your application outside the code. You can set these environment variables on the command line or use your project's existing tooling for managing environment variables.

If you are using a `.env` file, a template with the variable names is provided in the `.env.example` file located in the same directory as this README.

## Setting a Custom Timeout

You can set a custom timeout for the SDK's HTTP requests as follows:

```php
$sdk = new Client(timeout: 1000);
```

# Sample Usage

Below is a comprehensive example demonstrating how to authenticate and call a simple endpoint:

```php
<?php

use Celitech\Client;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $sdk->destinations->listDestinations();

print_r($response);

```

## Services

The SDK provides various services to interact with the API.

<details> 
<summary>Below is a list of all available services with links to their detailed documentation:</summary>

| Name |
| :--- |
| [OAuth](documentation/services/OAuth.md) |
| [Destinations](documentation/services/Destinations.md) |
| [Packages](documentation/services/Packages.md) |
| [Purchases](documentation/services/Purchases.md) |
| [ESim](documentation/services/ESim.md) |
</details>

## Models

The SDK includes several models that represent the data structures used in API requests and responses. These models help in organizing and managing the data efficiently.

<details> 
<summary>Below is a list of all available models with links to their detailed documentation:</summary>

| Name       | Description |
| :--------- | :---------- |
| [GetAccessTokenRequest](documentation/models/GetAccessTokenRequest.md) |  |
| [GetAccessTokenOkResponse](documentation/models/GetAccessTokenOkResponse.md) |  |
| [ListDestinationsOkResponse](documentation/models/ListDestinationsOkResponse.md) |  |
| [ListPackagesOkResponse](documentation/models/ListPackagesOkResponse.md) |  |
| [ListPurchasesOkResponse](documentation/models/ListPurchasesOkResponse.md) |  |
| [CreatePurchaseRequest](documentation/models/CreatePurchaseRequest.md) |  |
| [CreatePurchaseOkResponse](documentation/models/CreatePurchaseOkResponse.md) |  |
| [TopUpEsimRequest](documentation/models/TopUpEsimRequest.md) |  |
| [TopUpEsimOkResponse](documentation/models/TopUpEsimOkResponse.md) |  |
| [EditPurchaseRequest](documentation/models/EditPurchaseRequest.md) |  |
| [EditPurchaseOkResponse](documentation/models/EditPurchaseOkResponse.md) |  |
| [GetPurchaseConsumptionOkResponse](documentation/models/GetPurchaseConsumptionOkResponse.md) |  |
| [GetEsimOkResponse](documentation/models/GetEsimOkResponse.md) |  |
| [GetEsimDeviceOkResponse](documentation/models/GetEsimDeviceOkResponse.md) |  |
| [GetEsimHistoryOkResponse](documentation/models/GetEsimHistoryOkResponse.md) |  |
| [GetEsimMacOkResponse](documentation/models/GetEsimMacOkResponse.md) |  |
| [GrantType](documentation/models/GrantType.md) |  |
| [Destinations](documentation/models/Destinations.md) |  |
| [Packages](documentation/models/Packages.md) |  |
| [Purchases](documentation/models/Purchases.md) |  |
| [Package](documentation/models/Package.md) |  |
| [PurchasesEsim](documentation/models/PurchasesEsim.md) |  |
| [CreatePurchaseOkResponsePurchase](documentation/models/CreatePurchaseOkResponsePurchase.md) |  |
| [CreatePurchaseOkResponseProfile](documentation/models/CreatePurchaseOkResponseProfile.md) |  |
| [TopUpEsimOkResponsePurchase](documentation/models/TopUpEsimOkResponsePurchase.md) |  |
| [TopUpEsimOkResponseProfile](documentation/models/TopUpEsimOkResponseProfile.md) |  |
| [GetEsimOkResponseEsim](documentation/models/GetEsimOkResponseEsim.md) |  |
| [Device](documentation/models/Device.md) |  |
| [GetEsimHistoryOkResponseEsim](documentation/models/GetEsimHistoryOkResponseEsim.md) |  |
| [History](documentation/models/History.md) |  |
| [GetEsimMacOkResponseEsim](documentation/models/GetEsimMacOkResponseEsim.md) |  |
</details>

## License

This SDK is licensed under the MIT License.

See the [LICENSE](LICENSE) file for more details.


