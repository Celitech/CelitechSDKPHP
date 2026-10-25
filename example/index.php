<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Celitech\Client;

$client = new Client(clientId: '<clientId>', clientSecret: '<clientSecret>');
$client->destinations->listDestinations();
