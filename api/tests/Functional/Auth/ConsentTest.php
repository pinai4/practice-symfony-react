<?php

declare(strict_types=1);

namespace App\Tests\Functional\Auth;

use App\Tests\Functional\AuthWebTestCase;
use App\Tests\Functional\OAuth\OAuthFixture;
use App\Tests\Functional\OAuth\PKCE;

class ConsentTest extends AuthWebTestCase
{
    private const URI = '/consent';

    public function testWithoutOAuthParams(): void
    {
        $this->client->request('GET', self::URI);

        self::assertSame(400, $this->client->getResponse()->getStatusCode());
    }

    public function testWithOAuthParams(): void
    {
        $this->client->request(
            'GET',
            self::URI.'?'.http_build_query([
                'response_type' => 'code',
                'client_id' => OAuthFixture::CLIENT_IDENTIFIER,
                'code_challenge' => PKCE::challenge(PKCE::verifier()),
                'code_challenge_method' => 'S256',
                'scope' => OAuthFixture::SCOPE,
                'state' => 'sTaTe',
            ])
        );

        self::assertSame(200, $this->client->getResponse()->getStatusCode());

        self::assertNotEmpty($content = $this->client->getResponse()->getContent());
        self::assertStringContainsString('<button type="submit" name="allow" class="submit">Allow</button>', $content);
        self::assertStringContainsString('<button type="submit" name="deny" class="submit">Deny</button>', $content);
    }

    public function testGuestClickAllow(): void
    {
        $queryParams = http_build_query([
            'response_type' => 'code',
            'client_id' => OAuthFixture::CLIENT_IDENTIFIER,
            'code_challenge' => PKCE::challenge(PKCE::verifier()),
            'code_challenge_method' => 'S256',
            'scope' => OAuthFixture::SCOPE,
            'state' => 'sTaTe',
        ]);

        $this->client->request(
            'GET',
            self::URI.'?'.$queryParams
        );

        $this->client->submitForm('Allow');

        self::assertSame(302, $this->client->getResponse()->getStatusCode());
        self::assertSame('/authorize?'.$queryParams, $this->client->getResponse()->headers->get('Location'));

        $this->client->followRedirect();

        self::assertSame(302, $this->client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/login', $this->client->getResponse()->headers->get('Location'));
    }

    public function testClickAllow(): void
    {
        $this->logIn();

        $queryParams = http_build_query([
            'response_type' => 'code',
            'client_id' => OAuthFixture::CLIENT_IDENTIFIER,
            'code_challenge' => PKCE::challenge(PKCE::verifier()),
            'code_challenge_method' => 'S256',
            'scope' => OAuthFixture::SCOPE,
            'state' => 'sTaTe',
        ]);

        $this->client->request(
            'GET',
            self::URI.'?'.$queryParams
        );

        $this->client->submitForm('Allow');

        self::assertSame(302, $this->client->getResponse()->getStatusCode());
        self::assertSame(
            '/authorize?'.urldecode($queryParams),
            $this->client->getResponse()->headers->get('Location')
        );

        $this->client->followRedirect();

        self::assertSame(302, $this->client->getResponse()->getStatusCode());
        self::assertNotEmpty($location = $this->client->getResponse()->headers->get('Location'));

        /** @var array{query:string} $url */
        $url = parse_url($location);

        self::assertNotEmpty($url['query']);

        /* @var array{code:string,state:string} $query */
        parse_str($url['query'], $query);

        self::assertArrayHasKey('code', $query);
        self::assertNotEmpty($query['code']);
        self::assertArrayHasKey('state', $query);
        self::assertEquals('sTaTe', $query['state']);
    }

    public function testGuestClickDeny(): void
    {
        $queryParams = http_build_query([
            'response_type' => 'code',
            'client_id' => OAuthFixture::CLIENT_IDENTIFIER,
            'code_challenge' => PKCE::challenge(PKCE::verifier()),
            'code_challenge_method' => 'S256',
            'scope' => OAuthFixture::SCOPE,
            'state' => 'sTaTe',
        ]);

        $this->client->request(
            'GET',
            self::URI.'?'.$queryParams
        );

        $this->client->submitForm('Deny');

        self::assertSame(302, $this->client->getResponse()->getStatusCode());
        self::assertSame('/authorize?'.$queryParams, $this->client->getResponse()->headers->get('Location'));

        $this->client->followRedirect();

        self::assertSame(302, $this->client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/login', $this->client->getResponse()->headers->get('Location'));
    }

    public function testClickDeny(): void
    {
        $this->logIn();

        $queryParams = http_build_query([
            'response_type' => 'code',
            'client_id' => OAuthFixture::CLIENT_IDENTIFIER,
            'code_challenge' => PKCE::challenge(PKCE::verifier()),
            'code_challenge_method' => 'S256',
            'scope' => OAuthFixture::SCOPE,
            'state' => 'sTaTe',
        ]);

        $this->client->request(
            'GET',
            self::URI.'?'.$queryParams
        );

        $this->client->submitForm('Deny');

        self::assertSame(302, $this->client->getResponse()->getStatusCode());
        self::assertSame(
            '/authorize?'.urldecode($queryParams),
            $this->client->getResponse()->headers->get('Location')
        );

        $this->client->followRedirect();

        self::assertSame(302, $this->client->getResponse()->getStatusCode());
        self::assertNotEmpty($location = $this->client->getResponse()->headers->get('Location'));

        /** @var array{query:string} $url */
        $url = parse_url($location);

        self::assertNotEmpty($url['query']);

        /* @var array{code:string,state:string} $query */
        parse_str($url['query'], $query);

        self::assertArrayHasKey('error', $query);
        self::assertEquals('access_denied', $query['error']);

        self::assertArrayHasKey('error_description', $query);
        self::assertEquals(
            'The resource owner or authorization server denied the request.',
            $query['error_description']
        );

        self::assertArrayHasKey('hint', $query);
        self::assertEquals('The user denied the request', $query['hint']);

        self::assertArrayHasKey('message', $query);
        self::assertEquals('The resource owner or authorization server denied the request.', $query['message']);

        self::assertArrayHasKey('state', $query);
        self::assertEquals('sTaTe', $query['state']);
    }
}
