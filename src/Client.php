<?php

declare(strict_types=1);

namespace Celitech;

use Celitech\Services;
use Celitech\OAuth\TokenManager;

/**
 * Main SDK client providing access to all API service endpoints.
 *
 * This client acts as the central entry point for interacting with the API,
 * managing service instances, authentication, and base URL configuration.
 * Each service property provides access to a specific group of API endpoints.
 */
class Client
{
  public Services\Destinations $destinations;
  public Services\Packages $packages;
  public Services\V2 $v2;
  public Services\Topup $topup;
  public Services\Edit $edit;
  public Services\Consumption $consumption;
  public Services\Purchases $purchases;
  public Services\Device $device;
  public Services\History $history;
  public Services\Esim $esim;
  public Services\Token $token;
  private TokenManager $tokenManager;

  public function __construct(
    string $environment = Environment::Default,
    float $timeout = 10000,
    array $retryConfig = [],
    string $baseOAuthUrl = 'https://auth.celitech.net',
    string $clientId = '',
    string $clientSecret = ''
  ) {
    $this->tokenManager = new TokenManager(
      baseOAuthUrl: $baseOAuthUrl,
      clientId: $clientId,
      clientSecret: $clientSecret
    );

    $this->destinations = new Services\Destinations(
      $environment,
      $timeout,
      $retryConfig,
      $this->tokenManager
    );
    $this->packages = new Services\Packages(
      $environment,
      $timeout,
      $retryConfig,
      $this->tokenManager
    );
    $this->v2 = new Services\V2($environment, $timeout, $retryConfig, $this->tokenManager);
    $this->topup = new Services\Topup($environment, $timeout, $retryConfig, $this->tokenManager);
    $this->edit = new Services\Edit($environment, $timeout, $retryConfig, $this->tokenManager);
    $this->consumption = new Services\Consumption(
      $environment,
      $timeout,
      $retryConfig,
      $this->tokenManager
    );
    $this->purchases = new Services\Purchases(
      $environment,
      $timeout,
      $retryConfig,
      $this->tokenManager
    );
    $this->device = new Services\Device($environment, $timeout, $retryConfig, $this->tokenManager);
    $this->history = new Services\History(
      $environment,
      $timeout,
      $retryConfig,
      $this->tokenManager
    );
    $this->esim = new Services\Esim($environment, $timeout, $retryConfig, $this->tokenManager);
    $this->token = new Services\Token($environment, $timeout, $retryConfig, $this->tokenManager);
  }

  /**
   * Set the base URL for all API requests.
   *
   * This method updates the base URL for all service instances managed by this client.
   * Useful for switching between different environments or API versions at runtime.
   *
   * @param string $url The new base URL (e.g., 'https://api.example.com/v2')
   * @return void
   */
  public function setBaseUrl(string $url): void
  {
    $this->destinations->setBaseUrl($url);
    $this->packages->setBaseUrl($url);
    $this->v2->setBaseUrl($url);
    $this->topup->setBaseUrl($url);
    $this->edit->setBaseUrl($url);
    $this->consumption->setBaseUrl($url);
    $this->purchases->setBaseUrl($url);
    $this->device->setBaseUrl($url);
    $this->history->setBaseUrl($url);
    $this->esim->setBaseUrl($url);
    $this->token->setBaseUrl($url);
  }

  public function setBaseOAuthUrl(string $baseOAuthUrl): self
  {
    $this->tokenManager->setBaseOAuthUrl($baseOAuthUrl);
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
