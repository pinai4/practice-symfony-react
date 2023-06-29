<?php

declare(strict_types=1);

namespace App\Tests\Functional\OAuth;

use App\Tests\Functional\AuthWebTestCase;
use App\Tests\Functional\UserFixture;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use League\Bundle\OAuth2ServerBundle\OAuth2Grants;

class AuthorizationCodeGrantTest extends AuthWebTestCase
{
    use ArraySubsetAsserts;

    private const URI = '/token';

    public function testGetMethod(): void
    {
        $this->client->request('GET', self::URI);
        self::assertEquals(405, $this->client->getResponse()->getStatusCode());
    }

    public function testSuccess(): void
    {
        $verifier = PKCE::verifier();
        $challenge = PKCE::challenge($verifier);

        $code = OAuthHelper::generateEncryptedAuthCodePayload(
            OAuthFixture::createAuthorizationCode(UserFixture::IDENTIFIER),
            $challenge,
            'S256'
        );

        $this->client->request('POST', self::URI, [
            'grant_type' => OAuth2Grants::AUTHORIZATION_CODE,
            'code' => $code,
            'redirect_uri' => OAuthFixture::REDIRECT_URI,
            'client_id' => OAuthFixture::CLIENT_IDENTIFIER,
            'code_verifier' => $verifier,
        ]);

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());

        self::assertJson($content = $this->client->getResponse()->getContent());

        $data = json_decode($content, true);

        self::assertArraySubset([
                                    'token_type' => 'Bearer',
                                ], $data);

        self::assertArrayHasKey('expires_in', $data);
        self::assertNotEmpty($data['expires_in']);

        self::assertArrayHasKey('access_token', $data);
        self::assertNotEmpty($data['access_token']);

        self::assertArrayHasKey('refresh_token', $data);
        self::assertNotEmpty($data['refresh_token']);
    }

    public function testInvalidVerifier(): void
    {
        $verifier = PKCE::verifier();
        $challenge = PKCE::challenge($verifier);

        $code = OAuthHelper::generateEncryptedAuthCodePayload(
            OAuthFixture::createAuthorizationCode(UserFixture::IDENTIFIER),
            $challenge,
            'S256'
        );

        $this->client->request('POST', self::URI, [
            'grant_type' => OAuth2Grants::AUTHORIZATION_CODE,
            'code' => $code,
            'redirect_uri' => OAuthFixture::REDIRECT_URI,
            'client_id' => OAuthFixture::CLIENT_IDENTIFIER,
            'code_verifier' => PKCE::verifier(),
        ]);

        self::assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function testWithoutVerifier(): void
    {
        $verifier = PKCE::verifier();
        $challenge = PKCE::challenge($verifier);

        $code = OAuthHelper::generateEncryptedAuthCodePayload(
            OAuthFixture::createAuthorizationCode(UserFixture::IDENTIFIER),
            $challenge,
            'S256'
        );

        $this->client->request('POST', self::URI, [
            'grant_type' => OAuth2Grants::AUTHORIZATION_CODE,
            'code' => $code,
            'redirect_uri' => OAuthFixture::REDIRECT_URI,
            'client_id' => OAuthFixture::CLIENT_IDENTIFIER,
        ]);

        self::assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function testInvalidClient(): void
    {
        $verifier = PKCE::verifier();
        $challenge = PKCE::challenge($verifier);

        $code = OAuthHelper::generateEncryptedAuthCodePayload(
            OAuthFixture::createAuthorizationCode(UserFixture::IDENTIFIER),
            $challenge,
            'S256'
        );

        $this->client->request('POST', self::URI, [
            'grant_type' => OAuth2Grants::AUTHORIZATION_CODE,
            'code' => $code,
            'redirect_uri' => OAuthFixture::REDIRECT_URI,
            'client_id' => 'invalid',
            'code_verifier' => $verifier,
        ]);

        self::assertEquals(401, $this->client->getResponse()->getStatusCode());
    }
}
