<?php

declare(strict_types=1);

namespace Celitech\OAuth;

/**
 * Represents an OAuth 2.0 access token with metadata.
 *
 * This class encapsulates an OAuth access token along with its associated
 * scopes and expiration timestamp. Used internally by TokenManager for
 * token caching and validation.
 */
class OAuthToken
{
  /**
   * @var string The OAuth access token string
   */
  public string $accessToken;

  /**
   * @var array<string> List of OAuth scopes granted to this token
   */
  public array $scopes;

  /**
   * @var int|null Unix timestamp when the token expires (null if no expiration)
   */
  public ?int $expiresAt;

  /**
   * Create a new OAuth token instance.
   *
   * @param string $accessToken The access token string
   * @param array<string> $scopes The granted scopes
   * @param int|null $expiresAt Unix timestamp of expiration (null for non-expiring tokens)
   */
  public function __construct(string $accessToken, array $scopes, ?int $expiresAt)
  {
    $this->accessToken = $accessToken;
    $this->scopes = $scopes;
    $this->expiresAt = $expiresAt;
  }
}
