<?php

declare(strict_types=1);

namespace App\Tests\Functional\OAuth;

use App\Tests\Functional\AuthWebTestCase;
use App\Tests\Functional\UserFixture;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use League\Bundle\OAuth2ServerBundle\OAuth2Grants;

class PasswordGrantTest extends AuthWebTestCase
{
    use ArraySubsetAsserts;

    private const URI = '/token';

    /**
     * @throws \Exception
     */
    public function testSuccess(): void
    {
        $this->client->request('POST', self::URI, [
            'grant_type' => OAuth2Grants::PASSWORD,
            'username' => UserFixture::IDENTIFIER,
            'password' => UserFixture::PASSWORD_PLAIN,
            'client_id' => OAuthFixture::CLIENT_IDENTIFIER,
            'client_secret' => OAuthFixture::CLIENT_SECRET,
            'scope' => OAuthFixture::SCOPE,
        ]);

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());

        self::assertJson($content = (string) $this->client->getResponse()->getContent());

        self::assertIsArray($data = json_decode($content, true));
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
}
