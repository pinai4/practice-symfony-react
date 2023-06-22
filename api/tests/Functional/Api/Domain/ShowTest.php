<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Domain;

use App\Tests\Functional\AuthWebTestCase;
use DateInterval;
use DateTimeImmutable;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Exception;

class ShowTest extends AuthWebTestCase
{
    use ArraySubsetAsserts;

    private const URI = '/api/domains';
    private const WRONG_DOMAIN_ID = '11111111-1111-4111-1111-111111111111';
    private const WRONG_DOMAIN_NAME = 'some-wrong-domain.com';

    public function testGuest()
    {
        $this->client->request('GET', self::URI . '/'. DomainFixture::ID);

        $response = $this->client->getResponse();

        $this->assertSame(401, $response->getStatusCode());
    }

    public function testPost()
    {
        $this->client->request('POST', self::URI . '/'. DomainFixture::ID, [], [], [
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->getEncodedAccessToken()),
        ]);

        $response = $this->client->getResponse();

        $this->assertSame(405, $response->getStatusCode());
    }

    public function testWrongId()
    {
        $this->client->request('GET', self::URI . '/'. self::WRONG_DOMAIN_ID, [], [], [
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->getEncodedAccessToken()),
        ]);

        $response = $this->client->getResponse();

        $this->assertSame(404, $response->getStatusCode());
    }

    /**
     * @throws Exception
     */
    public function testSuccess()
    {
        $this->client->request('GET', self::URI . '/'. DomainFixture::ID, [], [], [
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->getEncodedAccessToken()),
        ]);

        $response = $this->client->getResponse();

        $this->assertSame(200, $response->getStatusCode());

        self::assertJson($content = $this->client->getResponse()->getContent());

        $data = json_decode($content, true);

        self::assertArrayHasKey('cr_date', $data);
        self::assertArrayHasKey('exp_date', $data);

        self::assertEquals(
            $data['exp_date'],
            (new DateTimeImmutable($data['cr_date']))->add(new DateInterval('P' . DomainFixture::PERIOD . 'Y'))->format(
                'Y-m-d H:i:s'
            )
        );

        self::assertArraySubset([
                               'id' => DomainFixture::ID,
                               'name' => DomainFixture::NAME,
                           ], $data);
    }

    public function testByNameGuest()
    {
        $this->client->request('GET', self::URI . '/'. DomainFixture::NAME);

        $response = $this->client->getResponse();

        $this->assertSame(401, $response->getStatusCode());
    }

    public function testByNamePost()
    {
        $this->client->request('POST', self::URI . '/'. DomainFixture::NAME);

        $response = $this->client->getResponse();

        $this->assertSame(405, $response->getStatusCode());
    }

    public function testByNameWrongName()
    {
        $this->client->request('GET', self::URI . '/'. self::WRONG_DOMAIN_NAME, [], [], [
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->getEncodedAccessToken()),
        ]);

        $response = $this->client->getResponse();

        $this->assertSame(404, $response->getStatusCode());
    }

    /**
     * @throws Exception
     */
    public function testByNameSuccess()
    {
        $this->client->request('GET', self::URI . '/'. DomainFixture::NAME, [], [], [
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->getEncodedAccessToken()),
        ]);

        $response = $this->client->getResponse();

        $this->assertSame(200, $response->getStatusCode());

        self::assertJson($content = $this->client->getResponse()->getContent());

        $data = json_decode($content, true);

        self::assertArrayHasKey('cr_date', $data);
        self::assertArrayHasKey('exp_date', $data);

        self::assertEquals(
            $data['exp_date'],
            (new DateTimeImmutable($data['cr_date']))->add(new DateInterval('P' . DomainFixture::PERIOD . 'Y'))->format(
                'Y-m-d H:i:s'
            )
        );

        self::assertArraySubset([
                                    'id' => DomainFixture::ID,
                                    'name' => DomainFixture::NAME,
                                ], $data);
    }









}