<?php

declare(strict_types=1);

namespace App\Tests\Functional\OAuth;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\CryptoException;
use League\Bundle\OAuth2ServerBundle\Entity\AccessToken as AccessTokenEntity;
use League\Bundle\OAuth2ServerBundle\Entity\Client as ClientEntity;
use League\Bundle\OAuth2ServerBundle\Entity\Scope as ScopeEntity;
use League\Bundle\OAuth2ServerBundle\Model\AccessToken as AccessTokenModel;
use League\Bundle\OAuth2ServerBundle\Model\AuthorizationCode as AuthorizationCodeModel;
use League\Bundle\OAuth2ServerBundle\Model\RefreshToken as RefreshTokenModel;
use League\OAuth2\Server\CryptKey;

final class OAuthHelper
{
    public const ENCRYPTION_KEY = 'test$secret';
    public const PRIVATE_KEY_PATH = '/app/tests/data/oauth/jwt_private.key';

    public static function generateEncryptedPayload(RefreshTokenModel $refreshToken): ?string
    {
        $payload = json_encode([
                                   'client_id' => $refreshToken->getAccessToken()->getClient()->getIdentifier(),
                                   'refresh_token_id' => $refreshToken->getIdentifier(),
                                   'access_token_id' => $refreshToken->getAccessToken()->getIdentifier(),
                                   'scopes' => array_map('strval', $refreshToken->getAccessToken()->getScopes()),
                                   'user_id' => $refreshToken->getAccessToken()->getUserIdentifier(),
                                   'expire_time' => $refreshToken->getExpiry()->getTimestamp(),
                               ]);

        try {
            return Crypto::encryptWithPassword($payload, self::ENCRYPTION_KEY);
        } catch (CryptoException $e) {
            return null;
        }
    }

    public static function generateEncryptedAuthCodePayload(
        AuthorizationCodeModel $authCode,
        string $challenge,
        string $challengeMethod
    ): ?string {
        $payload = json_encode([
                                   'client_id' => $authCode->getClient()->getIdentifier(),
                                   'redirect_uri' => (string)$authCode->getClient()->getRedirectUris()[0],
                                   'auth_code_id' => $authCode->getIdentifier(),
                                   'scopes' => array_map('strval', $authCode->getScopes()),
                                   'user_id' => $authCode->getUserIdentifier(),
                                   'expire_time' => $authCode->getExpiryDateTime()->getTimestamp(),
                                   'code_challenge' => $challenge,
                                   'code_challenge_method' => $challengeMethod,
                               ]);

        try {
            return Crypto::encryptWithPassword($payload, self::ENCRYPTION_KEY);
        } catch (CryptoException $e) {
            return null;
        }
    }

    public static function decryptPayload(string $payload): ?string
    {
        try {
            return Crypto::decryptWithPassword($payload, self::ENCRYPTION_KEY);
        } catch (CryptoException $e) {
            return null;
        }
    }

    public static function generateJwtToken(AccessTokenModel $accessToken): string
    {
        $clientEntity = new ClientEntity();
        $clientEntity->setIdentifier($accessToken->getClient()->getIdentifier());
        $clientEntity->setRedirectUri(array_map('strval', $accessToken->getClient()->getRedirectUris()));

        $accessTokenEntity = new AccessTokenEntity();
        $accessTokenEntity->setPrivateKey(new CryptKey(self::PRIVATE_KEY_PATH, null, false));
        $accessTokenEntity->setIdentifier($accessToken->getIdentifier());
        $accessTokenEntity->setExpiryDateTime($accessToken->getExpiry());
        $accessTokenEntity->setClient($clientEntity);
        $accessTokenEntity->setUserIdentifier($accessToken->getUserIdentifier());

        foreach ($accessToken->getScopes() as $scope) {
            $scopeEntity = new ScopeEntity();
            $scopeEntity->setIdentifier((string)$scope);

            $accessTokenEntity->addScope($scopeEntity);
        }

        return (string)$accessTokenEntity;
    }
}
