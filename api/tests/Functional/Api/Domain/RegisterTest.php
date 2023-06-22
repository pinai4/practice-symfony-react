<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Domain;

use App\Tests\Functional\Api\Contact\ContactFixture;
use App\Tests\Functional\AuthWebTestCase;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;

class RegisterTest extends AuthWebTestCase
{
    use ArraySubsetAsserts;

    private const URI = '/api/domains';

    private const ID = 'fcf44024-7349-4f38-a4fd-75c1c6379a43';
    private const DOMAIN_NAME = 'test-domain-registration.com';
    private const PERIOD = 1;

    public function testGuest()
    {
        $this->client->request('POST', self::URI, [], [], ['CONTENT_TYPE' => 'application/json']);

        $response = $this->client->getResponse();

        $this->assertSame(401, $response->getStatusCode());
    }

    public function testSuccess()
    {
        $this->client->request(
            'POST',
            self::URI,
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->getEncodedAccessToken()),
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                            'id' => self::ID,
                            'name' => self::DOMAIN_NAME,
                            'period' => self::PERIOD,
                            'owner_contact_id' => ContactFixture::ID,
                        ])
        );

        self::assertEquals(201, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = $this->client->getResponse()->getContent());

        $data = json_decode($content, true);

        self::assertEquals([], $data);
    }

    public function testNotValid(): void
    {
        $this->client->request(
            'POST',
            self::URI, [],
            [],
            [
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->getEncodedAccessToken()),
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                            'id' => '10001',
                            'name' => 'not-domain-name',
                            'period' => 0,
                            'ownerContactId' => '10001',
                        ])
        );

        self::assertEquals(422, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = $this->client->getResponse()->getContent());

        $data = json_decode($content, true);

        self::assertArraySubset([
                                    'errors' => [
                                        'id' => 'This is not a valid UUID.',
                                        'name' => 'This value is not a valid hostname.',
                                        'period' => 'This value should be between "1" and "10".',
                                        'ownerContactId' => 'This is not a valid UUID.',
                                    ]
                                ], $data);
    }

    public function testDuplicatedName(): void
    {
        $this->client->request(
            'POST',
            self::URI,
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->getEncodedAccessToken()),
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                            'id' => self::ID,
                            'name' => DomainFixture::NAME,
                            'period' => self::PERIOD,
                            'owner_contact_id' => ContactFixture::ID,

                        ])
        );

        self::assertEquals(409, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = $this->client->getResponse()->getContent());

        $data = json_decode($content, true);

        self::assertArraySubset(['message' => 'Domain already exists'], $data);
    }

    public function testDuplicatedId(): void
    {
        $this->client->request(
            'POST',
            self::URI,
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->getEncodedAccessToken()),
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                            'id' => DomainFixture::ID,
                            'name' => self::DOMAIN_NAME,
                            'period' => self::PERIOD,
                            'owner_contact_id' => ContactFixture::ID,
                        ])
        );

        self::assertEquals(409, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = $this->client->getResponse()->getContent());

        $data = json_decode($content, true);

        self::assertArraySubset(['message' => 'Domain already exists'], $data);
    }
}