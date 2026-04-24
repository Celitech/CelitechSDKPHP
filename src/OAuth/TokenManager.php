<?php

declare(strict_types=1);

namespace Celitech\OAuth;

use DateTime;
use DateTimeZone;

use Celitech\OAuth\OAuthToken;
use Celitech\Services\OAuth;
use Celitech\Models\GetAccessTokenRequest;
use Celitech\Models\GrantType;

/**
 * Manages OAuth 2.0 access tokens with automatic refresh and scope validation.
 *
 * This class handles token retrieval, caching, expiration checking, and automatic
 * refreshing. It ensures that a valid token with the required scopes is always
 * available before making authenticated API requests.
 */
class TokenManager
{
  private ?OAuthToken $token = null;
  private string $baseOAuthUrl;
  private string $clientId;
  private string $clientSecret;

  public function __construct(string $baseOAuthUrl, string $clientId, string $clientSecret)
  {
    $this->baseOAuthUrl = $baseOAuthUrl;
    $this->clientId = $clientId;
    $this->clientSecret = $clientSecret;
  }

  public function setBaseOAuthUrl(string $baseOAuthUrl): self
  {
    $this->baseOAuthUrl = $baseOAuthUrl;
    return $this;
  }

  public function setClientId(string $clientId): self
  {
    $this->clientId = $clientId;
    return $this;
  }

  public function setClientSecret(string $clientSecret): self
  {
    $this->clientSecret = $clientSecret;
    return $this;
  }

  /**
   * Get a valid OAuth access token with the required scopes.
   *
   * This method returns a cached token if it's valid and has all required scopes.
   * Otherwise, it fetches a new token from the OAuth endpoint. Tokens are automatically
   * refreshed before they expire based on the configured refresh buffer.
   *
   * @param array<string> $scopes The OAuth scopes required for the request
   * @return OAuthToken A valid OAuth token with the required scopes
   */
  public function getToken(array $scopes): OAuthToken
  {
    $timestamp = (new DateTime('now', new DateTimeZone('UTC')))->getTimestamp();
    $has_all_scopes = $this->token && !array_diff($scopes, $this->token->scopes);
    $valid_token =
      $this->token &&
      ($this->token->expiresAt === null || $this->token->expiresAt - $timestamp > 5000);
    if ($has_all_scopes && $valid_token) {
      return $this->token;
    }

    if ($this->token) {
      $scopes = array_unique(array_merge($scopes, $this->token->scopes));
    }
    $response = $this->getAccessToken($scopes);
    $expiresAt = isset($response['expires_in']) ? $timestamp + $response['expires_in'] : null;

    $this->token = new OAuthToken($response['access_token'], $scopes, $expiresAt);
    return $this->token;
  }

  /**
   * Clear the cached OAuth token.
   *
   * Forces the next token request to fetch a new token from the OAuth endpoint
   * instead of using a cached token.
   *
   * @return void
   */
  public function clean(): void
  {
    $this->token = null;
  }

  /**
   * Fetch a new access token from the OAuth endpoint.
   *
   * Makes an OAuth token request with the specified scopes and returns
   * the token response data.
   *
   * @param array<string> $scopes The OAuth scopes to request
   * @return array{access_token: string, expires_in?: int} Token response data
   */
  private function getAccessToken(array $scopes): array
  {
    $service = new OAuth(environment: $this->baseOAuthUrl);
    $input = new GetAccessTokenRequest(
      grantType: GrantType::ClientCredentials,
      clientId: $this->clientId,
      clientSecret: $this->clientSecret
    );

    $response = $service->getAccessToken(input: $input);

    return [
      'access_token' => $response->accessToken,
      'expires_in' => $response->expiresIn
    ];
  }
}
