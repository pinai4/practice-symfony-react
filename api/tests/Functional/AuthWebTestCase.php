<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Model\Auth\Entity\User\Role;
use App\Security\UserIdentity;
use App\Tests\Functional\OAuth\OAuthFixture;
use App\Tests\Functional\OAuth\OAuthHelper;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthWebTestCase extends WebTestCase
{
    protected KernelBrowser $client;

    private ?string $encodedAccessToken = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->client->disableReboot();
    }

    protected function getEncodedAccessToken(): string
    {
        if (is_null($this->encodedAccessToken)) {
            $this->encodedAccessToken = OAuthHelper::generateJwtToken(OAuthFixture::createAccessToken(UserFixture::IDENTIFIER));
        }

        return $this->encodedAccessToken;
    }

    protected function logIn(): void
    {
        $this->client->loginUser(
            new UserIdentity(
                UserFixture::ID,
                UserFixture::IDENTIFIER,
                UserFixture::PASSWORD,
                UserFixture::NAME,
                Role::user()->getName()
            )
        );
    }
}
