<?php

declare(strict_types=1);

namespace Celitech;

use Celitech\Services;

class Client
{
    public $OAuth;
    public $Destinations;
    public $Packages;
    public $Purchases;
    public $ESim;

    public function __construct(
        string $environment = Environment::Default,
        string $clientId = '',
        string $clientSecret = ''
    ) {
        $this->OAuth = new Services\OAuth($environment, $clientId, $clientSecret);
        $this->Destinations = new Services\Destinations($environment, $clientId, $clientSecret);
        $this->Packages = new Services\Packages($environment, $clientId, $clientSecret);
        $this->Purchases = new Services\Purchases($environment, $clientId, $clientSecret);
        $this->ESim = new Services\ESim($environment, $clientId, $clientSecret);
    }

    public function setBaseUrl(string $url)
    {
        $this->OAuth->setBaseUrl($url);
        $this->Destinations->setBaseUrl($url);
        $this->Packages->setBaseUrl($url);
        $this->Purchases->setBaseUrl($url);
        $this->ESim->setBaseUrl($url);
    }
}

// c029837e0e474b76bc487506e8799df5e3335891efe4fb02bda7a1441840310c
