# Celitech PHP Library

[![fern shield](https://img.shields.io/badge/%F0%9F%8C%BF-Built%20with%20Fern-brightgreen)](https://buildwithfern.com?utm_source=github&utm_medium=github&utm_campaign=readme&utm_source=Celitech%2FPHP)
[![php shield](https://img.shields.io/badge/php-packagist-pink)](https://packagist.org/packages/celitech-sdk/sdk)

The Celitech PHP library provides convenient access to the Celitech APIs from PHP.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Environments](#environments)
- [Exception Handling](#exception-handling)
- [Advanced](#advanced)
  - [Custom Client](#custom-client)
  - [Retries](#retries)
  - [Timeouts](#timeouts)
- [Contributing](#contributing)

## Requirements

This SDK requires PHP ^8.1.

## Installation

```sh
composer require celitech-sdk/sdk
```

The SDK sends its requests through a [PSR-18](https://www.php-fig.org/psr/psr-18/) HTTP client, which Composer installs for you only if your project allows the `php-http/discovery` plugin — add `"allow-plugins": {"php-http/discovery": true}` under `config` in your `composer.json`, or require a client yourself with `composer require guzzlehttp/guzzle` (or `composer require symfony/http-client nyholm/psr7`). Without either, the install succeeds with no HTTP client and the first API call fails at runtime.

## Usage

Instantiate and use the client with the following:

```php
<?php

namespace Example;

use Celitech\Client;
use Celitech\Purchases\Requests\CreatePurchaseV2Request;

$client = new Client(
    clientId: '<clientId>',
    clientSecret: '<clientSecret>',
);
$client->purchases->createPurchaseV2(
    new CreatePurchaseV2Request([
        'idempotencyKey' => '8f14e45f-ceea-4672-a1d9-0a5a3c2f1e17',
        'destination' => 'FRA',
        'dataLimitInGb' => 1,
        'quantity' => 1,
    ]),
);

```

## Environments

This SDK allows you to configure different environments for API requests. It defaults to the `Default_` environment.

```php
use Celitech\Client;
use Celitech\Environments;

$client = new Client(
    options: [
        'baseUrl' => Environments::Default_->value,
    ],
);
```

Available environments:
- `Environments::Default_`

`baseUrl` also accepts any URL string, such as a staging server or a local mock.

## Exception Handling

When the API returns a non-success status code (4xx or 5xx response), an exception will be thrown.
A successful response whose body does not match the expected type raises `CelitechException`, with the underlying error as its previous exception.

```php
use Celitech\Exceptions\CelitechApiException;
use Celitech\Exceptions\CelitechException;

try {
    $response = $client->purchases->createPurchaseV2(...);
} catch (CelitechApiException $e) {
    echo 'API Exception occurred: ' . $e->getMessage() . "\n";
    echo 'Status Code: ' . $e->getCode() . "\n";
    echo 'Response Body: ' . $e->getBody() . "\n";
    // Optionally, rethrow the exception or handle accordingly.
}
```

## Advanced

### Custom Client

This SDK is built to work with any HTTP client that implements the [PSR-18](https://www.php-fig.org/psr/psr-18/) `ClientInterface`.
By default, if no client is provided, the SDK will use `php-http/discovery` to find an installed HTTP client.
However, you can pass your own client that adheres to `ClientInterface`:

```php
use Celitech\Client;

// Pass any PSR-18 compatible HTTP client implementation.
// For example, using Guzzle:
$customClient = new \GuzzleHttp\Client([
    'timeout' => 5.0,
]);

$client = new Client(options: [
    'client' => $customClient
]);
```

### Retries

The SDK is instrumented with automatic retries with exponential backoff. A request will be retried as long
as the request is deemed retryable and the number of retry attempts has not grown larger than the configured
retry limit (default: 3).

A request is deemed retryable when any of the following HTTP status codes is returned:

- [408](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/408) (Timeout)
- [429](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/429) (Too Many Requests)
- [5XX](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status#server_error_responses) (Internal Server Error)

Use the `maxRetries` request option to configure this behavior.

```php
$response = $client->purchases->createPurchaseV2(
    ...,
    options: [
        'maxRetries' => 0 // Override maxRetries at the request level
    ]
);
```

### Timeouts

The SDK applies no timeout unless you ask for one, so your HTTP client's own default applies — Guzzle's is unlimited,
which lets a request wait indefinitely on a server that never answers. Use the `timeout` option, in
seconds, to configure this behavior for every request:

```php
use Celitech\Client;

$client = new Client(options: [
    'timeout' => 10.0 // Applied to every request
]);
```

or for a single call:

```php
$response = $client->purchases->createPurchaseV2(
    ...,
    options: [
        'timeout' => 3.0 // Override timeout at the request level
    ]
);
```

The SDK forwards `timeout` to Guzzle and Symfony HttpClient, the clients it configures directly;
other PSR-18 clients may ignore it, so set the timeout on that client instead.

## Contributing

While we value open-source contributions to this SDK, this library is generated programmatically.
Additions made directly to this library would have to be moved over to our generation code,
otherwise they would be overwritten upon the next generated release. Feel free to open a PR as
a proof of concept, but know that we will not be able to merge it as-is. We suggest opening
an issue first to discuss with us!

On the other hand, contributions to the README are always very welcome!
