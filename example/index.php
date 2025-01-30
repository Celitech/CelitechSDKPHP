<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Celitech\Models;

use Celitech\Client;

$sdk = new Client(clientId: 'CLIENT_ID', clientSecret: 'CLIENT_SECRET');

$response = $sdk->destinations->listDestinations();

print_r($response);
