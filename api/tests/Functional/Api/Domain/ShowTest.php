<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Domain;

use App\Tests\Functional\AuthWebTestCase;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;

class ShowTest extends AuthWebTestCase
{
    use ArraySubsetAsserts;

    private const URI = '/api/domains';
    private const WRONG_DOMAIN_ID = '11111111-1111-4111-1111-111111111111';
    private const WRONG_DOMAIN_NAME = 'some-wrong-domain.com';

    public function testGuest(): void
    {
        $this->client->request('GET', self::URI.'/'.DomainFixture::ID);

        $response = $this->client->getResponse();

        $this->assertSame(401, $response->getStatusCode());
    }

    public function testPost(): void
    {
        $this->client->request('POST', self::URI.'/'.DomainFixture::ID, [], [], [
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->getEncodedAccessToken()),
        ]);

        $response = $this->client->getResponse();

        $this->assertSame(405, $response->getStatusCode());
    }

    public function testWrongId(): void
    {
        $this->client->request('GET', self::URI.'/'.self::WRONG_DOMAIN_ID, [], [], [
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->getEncodedAccessToken()),
        ]);

        $response = $this->client->getResponse();

        $this->assertSame(404, $response->getStatusCode());
    }

    /**
     * @throws \Exception
     */
    public function testSuccess(): void
    {
        $this->client->request('GET', self::URI.'/'.DomainFixture::ID, [], [], [
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->getEncodedAccessToken()),
        ]);

        $response = $this->client->getResponse();

        $this->assertSame(200, $response->getStatusCode());

        self::assertJson($content = (string) $this->client->getResponse()->getContent());

        self::assertIsArray($data = json_decode($content, true));
        self::assertArrayHasKey('cr_date', $data);
        self::assertIsString($data['cr_date']);
        self::assertArrayHasKey('exp_date', $data);

        self::assertEquals(
            $data['exp_date'],
            (new \DateTimeImmutable($data['cr_date']))->add(new \DateInterval('P'.DomainFixture::PERIOD.'Y'))->format(
                'Y-m-d H:i:s'
            )
        );

        self::assertArraySubset([
            'id' => DomainFixture::ID,
            'name' => DomainFixture::NAME,
        ], $data);
    }

    public function testByNameGuest(): void
    {
        $this->client->request('GET', self::URI.'/'.DomainFixture::NAME);

        $response = $this->client->getResponse();

        $this->assertSame(401, $response->getStatusCode());
    }

    public function testByNamePost(): void
    {
        $this->client->request('POST', self::URI.'/'.DomainFixture::NAME);

        $response = $this->client->getResponse();

        $this->assertSame(405, $response->getStatusCode());
    }

    public function testByNameWrongName(): void
    {
        $this->client->request('GET', self::URI.'/'.self::WRONG_DOMAIN_NAME, [], [], [
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->getEncodedAccessToken()),
        ]);

        $response = $this->client->getResponse();

        $this->assertSame(404, $response->getStatusCode());
    }

    /**
     * @throws \Exception
     */
    public function testByNameSuccess(): void
    {
        $this->client->request('GET', self::URI.'/'.DomainFixture::NAME, [], [], [
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->getEncodedAccessToken()),
        ]);

        $response = $this->client->getResponse();

        $this->assertSame(200, $response->getStatusCode());

        self::assertJson($content = (string) $this->client->getResponse()->getContent());

        self::assertIsArray($data = json_decode($content, true));
        self::assertArrayHasKey('cr_date', $data);
        self::assertIsString($data['cr_date']);
        self::assertArrayHasKey('exp_date', $data);

        self::assertEquals(
            $data['exp_date'],
            (new \DateTimeImmutable($data['cr_date']))->add(new \DateInterval('P'.DomainFixture::PERIOD.'Y'))->format(
                'Y-m-d H:i:s'
            )
        );

        self::assertArraySubset([
            'id' => DomainFixture::ID,
            'name' => DomainFixture::NAME,
        ], $data);
    }
}
