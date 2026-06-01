# Celitech PHP SDK 2.0.1


Welcome to the Celitech SDK documentation. This guide will help you get started with integrating and using the Celitech SDK in your project.

## Versions

- API version: `2.0.1`
- SDK version: `2.0.1`

## About the API

Welcome to the CELITECH API documentation!

Useful links: [Homepage](https://www.celitech.com) | [Support email](mailto:support@celitech.com) | [Blog](https://www.celitech.com/blog/)


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

# Setup & Configuration

## Supported Language Versions

This SDK is compatible with the following versions: `PHP >= 8.1`

## Installation

### Using a local path (recommended for development)

To use the SDK in your project before it is published to Packagist, add a `path` repository entry in your project's `composer.json` pointing to the SDK directory:

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "/path/to/celitech-sdk/sdk"
    }
  ]
}
```

Then run:

```bash
composer require celitech-sdk/sdk
```

### Using Packagist or a private registry

If the package is published to Packagist or a private Composer registry, install directly:

```bash
composer require celitech-sdk/sdk
```

## Verifying the SDK setup

To verify the SDK works correctly, you can run the included example project:

1. Install dependencies:

```bash
composer install
```

2. Run the example:

```bash
php example/index.php
```

## Authentication


### OAuth Authentication

The Celitech API uses OAuth 2.0 for authentication.

You need to provide your OAuth credentials when initializing the SDK. Tokens are automatically fetched, cached, and refreshed — you do not need to manage them yourself.

```php
new Client(
    clientId: 'CLIENT_ID',
    clientSecret: 'CLIENT_SECRET'
)
```

If you need to set or update the OAuth credentials after the SDK initialization, you can use:

```php
$sdk->setClientId('CLIENT_ID')
$sdk->setClientSecret('CLIENT_SECRET')
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
| [Destinations](documentation/services/Destinations.md) |
| [Packages](documentation/services/Packages.md) |
| [Purchases](documentation/services/Purchases.md) |
| [ESim](documentation/services/ESim.md) |
| [IFrame](documentation/services/IFrame.md) |
</details>


## Models

The SDK includes several models that represent the data structures used in API requests and responses. These models help in organizing and managing the data efficiently.


<details>
<summary>Below is a list of all available models with links to their detailed documentation:</summary>

| Name       | Description |
| :--------- | :---------- |
| [ListDestinationsOkResponse](documentation/models/ListDestinationsOkResponse.md) |  |
| [ListPackagesOkResponse](documentation/models/ListPackagesOkResponse.md) |  |
| [CreatePurchaseV2Request](documentation/models/CreatePurchaseV2Request.md) |  |
| [CreatePurchaseV2OkResponse](documentation/models/CreatePurchaseV2OkResponse.md) |  |
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
| [TokenOkResponse](documentation/models/TokenOkResponse.md) |  |
| [GrantType](documentation/models/GrantType.md) |  |
</details>


## License

This SDK is licensed under the MIT License.

See the [LICENSE](LICENSE) file for more details.


