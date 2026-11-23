# Celitech PHP SDK 3.0.0

Welcome to the Celitech SDK documentation. This guide will help you get started with integrating and using the Celitech SDK in your project.

## Versions

- API version: `3.0.0`
- SDK version: `3.0.0`

## About the API

Welcome to the CELITECH API documentation!

Useful links: [Homepage](https://www.celitech.com) | [Support email](mailto:devops@celitech.com) | [Blog](https://www.celitech.com/blog/)

# Introduction

This is the reference documentation and schemas for the CELITECH API and webhooks. For tutorials and other documentation please refer to the [CELITECH Documentation](https://docs.celitech.com).

"Partners" refers to online service providers that use our eSIM API. Access levels include Gold, Platinum, and Diamond.

## API

The CELITECH API is designed for use by partner platforms, including both web and mobile applications. It's assumed all endpoint calls are initiated from the backend of an integrated platform.

API URL: `https://api.celitech.net/v1`

## Authentication & Authorization
CELITECH API uses the OAuth 2.0 protocol for authentication and authorization.
The endpoints are protected using client credentials flow which is based on a token exchange. The token has a defined life span (typically 1 hour), after which a new token must be obtained.

To begin, obtain OAuth 2.0 client credentials ( **CLIENT_ID** & **CLIENT_SECRET** ) from the [CELITECH Dashboard](https://www.dashboard.celitech.com/). Then your client application requests an access token from the CELITECH Authorization Server, extracts a token from the response, and sends the token to the CELITECH API that you want to access.

Security Scheme Type: `OAuth2`

Flow type: `clientCredentials`

Token URL: `https://auth.celitech.net/oauth2/token`

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

## Environment Variables

These are the environment variables for the SDK:

| Name          | Description             |
| :------------ | :---------------------- |
| CLIENT_ID     | Client ID parameter     |
| CLIENT_SECRET | Client Secret parameter |

Environment variables are a way to configure your application outside the code. You can set these environment variables on the command line or use your project's existing tooling for managing environment variables.

If you are using a `.env` file, a template with the variable names is provided in the `.env.example` file located in the same directory as this README.

## Setting a Custom Timeout

You can set a custom timeout for the SDK's HTTP requests as follows:

```php
$sdk = new Client(options: ['timeout' => 10], clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');
```

# Sample Usage

Below is a comprehensive example demonstrating how to authenticate and call a simple endpoint:

```php
<?php

use Celitech\Client;

$client = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $client->destinations->listDestinations();

print_r($response);
```

## Services

The SDK provides various services to interact with the API.

<details>
<summary>Below is a list of all available services with links to their detailed documentation:</summary>

| Name                                                   |
| :----------------------------------------------------- |
| [Destinations](documentation/services/Destinations.md) |
| [Packages](documentation/services/Packages.md)         |
| [Purchases](documentation/services/Purchases.md)       |
| [ESim](documentation/services/ESim.md)                 |
| [IFrame](documentation/services/IFrame.md)             |

</details>

## Models

The SDK includes several models that represent the data structures used in API requests and responses. These models help in organizing and managing the data efficiently.

<details>
<summary>Below is a list of all available models with links to their detailed documentation:</summary>

| Name                                                                                     | Description |
| :--------------------------------------------------------------------------------------- | :---------- |
| [ListDestinationsResponse](documentation/models/ListDestinationsResponse.md)             |             |
| [ErrorResponse](documentation/models/ErrorResponse.md)                                   |             |
| [ListPackagesResponse](documentation/models/ListPackagesResponse.md)                     |             |
| [CreatePurchaseV2Request](documentation/models/CreatePurchaseV2Request.md)               |             |
| [CreatePurchaseV2ResponseItem](documentation/models/CreatePurchaseV2ResponseItem.md)     |             |
| [ListPurchasesResponse](documentation/models/ListPurchasesResponse.md)                   |             |
| [CreatePurchaseRequest](documentation/models/CreatePurchaseRequest.md)                   |             |
| [CreatePurchaseResponse](documentation/models/CreatePurchaseResponse.md)                 |             |
| [TopUpEsimRequest](documentation/models/TopUpEsimRequest.md)                             |             |
| [TopUpEsimResponse](documentation/models/TopUpEsimResponse.md)                           |             |
| [EditPurchaseRequest](documentation/models/EditPurchaseRequest.md)                       |             |
| [EditPurchaseResponse](documentation/models/EditPurchaseResponse.md)                     |             |
| [GetPurchaseConsumptionResponse](documentation/models/GetPurchaseConsumptionResponse.md) |             |
| [GetEsimResponse](documentation/models/GetEsimResponse.md)                               |             |
| [GetEsimDeviceResponse](documentation/models/GetEsimDeviceResponse.md)                   |             |
| [GetEsimHistoryResponse](documentation/models/GetEsimHistoryResponse.md)                 |             |
| [SuspendEsimResponse](documentation/models/SuspendEsimResponse.md)                       |             |
| [TokenResponse](documentation/models/TokenResponse.md)                                   |             |

</details>

## License

This SDK is licensed under the MIT License.

See the [LICENSE](LICENSE) file for more details.
