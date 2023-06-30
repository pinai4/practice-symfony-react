<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Contact;

use App\Tests\Functional\AuthWebTestCase;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;

class CreateTest extends AuthWebTestCase
{
    use ArraySubsetAsserts;

    private const URI = '/api/contacts';

    public const ID = '9993ced7-bcac-49a7-ba55-b67a7e533615';
    public const NAME = 'TestName TestSurname';
    public const ORGANIZATION = 'My Company';
    public const EMAIL = 'test-new@email.com';
    public const PHONE = '+7.0958888888';
    public const ADDRESS = 'New Address';
    public const CITY = 'Moscow';
    public const STATE = 'Moscowskay oblast';
    public const ZIP = '30003';
    public const COUNTRY = 'ru';

    public function testGuest(): void
    {
        $this->client->request('POST', self::URI, [], [], ['CONTENT_TYPE' => 'application/json']);

        $response = $this->client->getResponse();

        $this->assertSame(401, $response->getStatusCode());
    }

    public function testSuccess(): void
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
                'name' => self::NAME,
                'organization' => self::ORGANIZATION,
                'email' => self::EMAIL,
                'phone' => self::PHONE,
                'address' => self::ADDRESS,
                'city' => self::CITY,
                'state' => self::STATE,
                'zip' => self::ZIP,
                'country' => self::COUNTRY,
            ])
        );

        self::assertEquals(201, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = (string) $this->client->getResponse()->getContent());

        self::assertIsArray($data = json_decode($content, true));
        self::assertEquals([], $data);
    }

    /**
     * @throws \Exception
     */
    public function testNotValid(): void
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
                'id' => '10001',
                'name' => 'Tester',
                'email' => 'invalid-email',
                'phone' => '44444444',
                'address' => '',
                'state' => '',
                'zip' => '',
                'country' => '',
            ])
        );

        self::assertEquals(422, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = (string) $this->client->getResponse()->getContent());

        self::assertIsArray($data = json_decode($content, true));
        self::assertArraySubset([
            'errors' => [
                'id' => 'This is not a valid UUID.',
                'name' => 'This value is not valid.',
                'email' => 'This value is not a valid email address.',
                'phone' => 'This value is not valid.',
                'address' => 'This value should not be blank.',
                'city' => 'This value should not be blank.',
                'state' => 'This value should not be blank.',
                'zip' => 'This value should not be blank.',
                'country' => 'This value should not be blank.',
            ],
        ], $data);
    }

    /**
     * @throws \Exception
     */
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
                'id' => ContactFixture::ID,
                'name' => self::NAME,
                'organization' => self::ORGANIZATION,
                'email' => self::EMAIL,
                'phone' => self::PHONE,
                'address' => self::ADDRESS,
                'city' => self::CITY,
                'state' => self::STATE,
                'zip' => self::ZIP,
                'country' => self::COUNTRY,
            ])
        );

        self::assertEquals(409, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = (string) $this->client->getResponse()->getContent());

        self::assertIsArray($data = json_decode($content, true));
        self::assertArraySubset(['message' => 'Contact already exists'], $data);
    }
}
