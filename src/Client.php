<?php

declare(strict_types=1);

namespace Celitech;

use Celitech\Destinations\DestinationsClient;
use Celitech\Packages\PackagesClient;
use Celitech\Purchases\PurchasesClient;
use Celitech\ESim\ESimClient;
use Celitech\IFrame\IFrameClient;
use Celitech\OAuth\OAuthClient;
use Celitech\OAuth\TokenManager;
use Celitech\Core\Client\RawClient;

/**
 * Main SDK client providing access to all API service endpoints.
 *
 * This client acts as the central entry point for interacting with the API,
 * managing service instances, authentication, and base URL configuration.
 * Each service property provides access to a specific group of API endpoints.
 */
class Client
{
  public DestinationsClient $destinations;
  public PackagesClient $packages;
  public PurchasesClient $purchases;
  public ESimClient $eSim;
  public IFrameClient $iFrame;
  public OAuthClient $oAuth;
  private TokenManager $tokenManager;
  private array $options;
  /** @var RawClient $client */
  private RawClient $client;

  public function __construct(
    ?array $options = null,
    string $baseOAuthUrl = 'https://auth.celitech.net',
    string $clientId = '',
    string $clientSecret = ''
  ) {
    $defaultHeaders = [
      'X-Fern-Language' => 'PHP',
      'X-Fern-SDK-Name' => 'Celitech'
    ];

    $this->options = $options ?? [];
    $this->options['headers'] = array_merge($defaultHeaders, $this->options['headers'] ?? []);

    $this->client = new RawClient(options: $this->options);
    $this->tokenManager = new TokenManager(
      baseOAuthUrl: $baseOAuthUrl,
      clientId: $clientId,
      clientSecret: $clientSecret,
      options: $this->options
    );

    $this->destinations = new DestinationsClient(
      $this->client,
      $this->options,
      $this->tokenManager
    );
    $this->packages = new PackagesClient($this->client, $this->options, $this->tokenManager);
    $this->purchases = new PurchasesClient($this->client, $this->options, $this->tokenManager);
    $this->eSim = new ESimClient($this->client, $this->options, $this->tokenManager);
    $this->iFrame = new IFrameClient($this->client, $this->options, $this->tokenManager);
    $this->oAuth = new OAuthClient($this->client, $this->options, $this->tokenManager);
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
    $this->purchases->setBaseUrl($url);
    $this->eSim->setBaseUrl($url);
    $this->iFrame->setBaseUrl($url);
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
