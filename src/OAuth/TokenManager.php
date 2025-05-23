<?php
namespace Celitech\OAuth;

use DateTime;
use DateTimeZone;

use Celitech\OAuth\OAuthToken;
use Celitech\Services\OAuth;
use Celitech\Models\GetAccessTokenRequest;
use Celitech\Models\GrantType;

class TokenManager
{
    private ?OauthToken $token = null;
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

    public function getToken(array $scopes): OauthToken
    {
        if ($this->token && !array_diff($scopes, $this->token->scopes)) {
            return $this->token;
        }

        if ($this->token) {
            $scopes = array_unique(array_merge($scopes, $this->token->scopes));
        }

        $response = $this->getAccessToken($scopes);
        $expiresAt = isset($response['expires_in'])
            ? (new DateTime('now', new DateTimeZone('UTC')))->getTimestamp() + $response['expires_in']
            : null;

        $this->token = new OauthToken($response['access_token'], $scopes, $expiresAt);
        return $this->token;
    }

    public function clean(): void
    {
        $this->token = null;
    }

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
            'expires_in' => $response->expiresIn,
        ];
    }
}
