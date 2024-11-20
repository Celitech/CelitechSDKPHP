<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Celitech\Models;

use Celitech\Client;
use Celitech\Models\GetAccessTokenRequest;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$input = new Models\GetAccessTokenRequest();

$response = $sdk->OAuth->getAccessToken(input: $input);

print_r($response);
