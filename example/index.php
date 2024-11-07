<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Celitech\Models;

use Celitech\Client;
use Celitech\Models\GetAccessTokenRequest;

$sdk = new Client(clientId: 'client-id', clientSecret: 'client-secret');

$input = new Models\GetAccessTokenRequest(grantType: $grantType, clientId: 'client_id', clientSecret: 'client_secret');

$response = $sdk->OAuth->getAccessToken(input: $input);

print_r($response);
