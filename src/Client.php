<?php

declare(strict_types=1);

namespace Celitech;

use Celitech\Services;
use Celitech\OAuth\TokenManager;

class Client
{
    public $oAuth;
    public $destinations;
    public $packages;
    public $purchases;
    public $eSim;
    public $iframe;
    private TokenManager $tokenManager;

    public function __construct(
        string $environment = Environment::Default,
        float $timeout = 0,
        string $baseOAuthUrl = 'https://auth.celitech.net',
        string $clientId = '',
        string $clientSecret = ''
    ) {
        $tokenManager = new TokenManager(baseOAuthUrl: $baseOAuthUrl, clientId: $clientId, clientSecret: $clientSecret);

        $this->oAuth = new Services\OAuth($environment, $timeout, $tokenManager);
        $this->destinations = new Services\Destinations($environment, $timeout, $tokenManager);
        $this->packages = new Services\Packages($environment, $timeout, $tokenManager);
        $this->purchases = new Services\Purchases($environment, $timeout, $tokenManager);
        $this->eSim = new Services\ESim($environment, $timeout, $tokenManager);
        $this->iframe = new Services\Iframe($environment, $timeout, $tokenManager);
    }

    public function setBaseUrl(string $url)
    {
        $this->oAuth->setBaseUrl($url);
        $this->destinations->setBaseUrl($url);
        $this->packages->setBaseUrl($url);
        $this->purchases->setBaseUrl($url);
        $this->eSim->setBaseUrl($url);
        $this->iframe->setBaseUrl($url);
    }

    public function setBaseOAuthUrl(string $baseOAuthUrl): self
    {
        $this->baseOAuthUrl = $baseOAuthUrl;
        return $this;
    }

    public function setClientId(string $clientId): self
    {
        $this->tokenManager->setClientId($clientId);
        return $this;
    }

    public function setClientSecret(string $clientSecret): self
    {
        $this->tokenManager->setClientSecret($clientSecret);
        return $this;
    }
}

// c029837e0e474b76bc487506e8799df5e3335891efe4fb02bda7a1441840310c
