<?php

declare(strict_types=1);

namespace App\Tests\Functional\OAuth;

use App\Model\Auth\Entity\User\User;
use App\Tests\Functional\UserFixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use League\Bundle\OAuth2ServerBundle\Model\AccessToken;
use League\Bundle\OAuth2ServerBundle\Model\AuthorizationCode;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use League\Bundle\OAuth2ServerBundle\Model\Grant;
use League\Bundle\OAuth2ServerBundle\Model\RedirectUri;
use League\Bundle\OAuth2ServerBundle\Model\RefreshToken;
use League\Bundle\OAuth2ServerBundle\Model\Scope;
use League\Bundle\OAuth2ServerBundle\OAuth2Grants;

class OAuthFixture extends Fixture implements DependentFixtureInterface
{
    public const CLIENT_NAME = 'OAuth Test Client';
    public const CLIENT_IDENTIFIER = 'oauth';
    public const CLIENT_SECRET = null;

    public const SCOPE = 'common';

    public const REDIRECT_URI = 'http://localhost:8080/oauth';

    public const ACCESS_TOKEN_USER_BOUND = '96fb0ff864bf242425bfa7b9b6f47294fda556bf5eef78f753f61c2b827125d37d5d5735bcaed5b8';
    public const ACCESS_TOKEN_EXPIRY = '2200-12-31 01:02:03';

    public const REFRESH_TOKEN_USER_BOUND = 'd76520ef128d2d607c7f323d57ef5ca0c91bde1a9096154504831a8b7afff902442e668568124b84';
    public const REFRESH_TOKEN_EXPIRY = '2300-12-31 01:02:03';

    public const AUTHORIZATION_CODE_USER_BOUND = '8be866c9619a34f5e97e734f1a9cd05e38e7ed3777d633c7414e663b8d9b9bbbb1ece086488ae0f8';
    public const AUTHORIZATION_CODE_EXPIRY = '2400-12-31 01:02:03';

    public function load(ObjectManager $manager): void
    {
        /** @var User $user */
        $user = $this->getReference(UserFixture::REFERENCE);

        $client = self::createClient();
        $manager->persist($client);

        $accessToken = self::createAccessToken($user->getEmail()->getValue(), $client);
        $manager->persist($accessToken);

        $refreshToken = self::createRefreshToken($user->getEmail()->getValue(), $accessToken);
        $manager->persist($refreshToken);

        $authorizationCode = self::createAuthorizationCode($user->getEmail()->getValue(), $client);
        $manager->persist($authorizationCode);

        $manager->flush();
    }

    public static function createClient(): Client
    {
        $client = new Client(self::CLIENT_NAME, self::CLIENT_IDENTIFIER, self::CLIENT_SECRET);
        $client->setGrants(
            new Grant(OAuth2Grants::AUTHORIZATION_CODE),
            new Grant(OAuth2Grants::PASSWORD),
            new Grant(OAuth2Grants::REFRESH_TOKEN)
        );
        $client->setScopes(new Scope(self::SCOPE));
        $client->setRedirectUris(new RedirectUri(self::REDIRECT_URI));

        return $client;
    }

    public static function createAccessToken(string $userIdentifier, ?Client $client = null): AccessToken
    {
        if (is_null($client)) {
            $client = self::createClient();
        }

        return new AccessToken(
            self::ACCESS_TOKEN_USER_BOUND,
            new \DateTimeImmutable(self::ACCESS_TOKEN_EXPIRY),
            $client,
            $userIdentifier,
            [new Scope(self::SCOPE)]
        );
    }

    public static function createAuthorizationCode(string $userIdentifier, ?Client $client = null): AuthorizationCode
    {
        if (is_null($client)) {
            $client = self::createClient();
        }

        return new AuthorizationCode(
            self::AUTHORIZATION_CODE_USER_BOUND,
            new \DateTimeImmutable(self::AUTHORIZATION_CODE_EXPIRY),
            $client,
            $userIdentifier,
            [new Scope(self::SCOPE)]
        );
    }

    public static function createRefreshToken(string $userIdentifier, ?AccessToken $accessToken = null): RefreshToken
    {
        if (is_null($accessToken)) {
            $accessToken = self::createAccessToken($userIdentifier);
        }

        return new RefreshToken(
            self::REFRESH_TOKEN_USER_BOUND,
            new \DateTimeImmutable(self::REFRESH_TOKEN_EXPIRY),
            $accessToken
        );
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
        ];
    }
}
