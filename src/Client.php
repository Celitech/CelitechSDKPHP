<?php

declare(strict_types=1);

namespace Celitech;

use Celitech\Services;

class Client
{
    public $oAuth;
    public $destinations;
    public $packages;
    public $purchases;
    public $eSim;

    public function __construct(
        string $environment = Environment::Default,
        float $timeout = 0,
        string $clientId = '',
        string $clientSecret = ''
    ) {
        $this->oAuth = new Services\OAuth($environment, $timeout, $clientId, $clientSecret);
        $this->destinations = new Services\Destinations($environment, $timeout, $clientId, $clientSecret);
        $this->packages = new Services\Packages($environment, $timeout, $clientId, $clientSecret);
        $this->purchases = new Services\Purchases($environment, $timeout, $clientId, $clientSecret);
        $this->eSim = new Services\ESim($environment, $timeout, $clientId, $clientSecret);
    }

    public function setBaseUrl(string $url)
    {
        $this->oAuth->setBaseUrl($url);
        $this->destinations->setBaseUrl($url);
        $this->packages->setBaseUrl($url);
        $this->purchases->setBaseUrl($url);
        $this->eSim->setBaseUrl($url);
    }
}

// c029837e0e474b76bc487506e8799df5e3335891efe4fb02bda7a1441840310c
