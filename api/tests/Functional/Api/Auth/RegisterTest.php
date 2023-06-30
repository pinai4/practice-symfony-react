<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Auth;

use App\Tests\Functional\AuthWebTestCase;
use App\Tests\Functional\UserFixture;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;

class RegisterTest extends AuthWebTestCase
{
    use ArraySubsetAsserts;

    private const URI = '/api/auth/register';

    private const ID = 'ce83a675-c1ac-4cfd-8a11-d4b6fdf52faa';
    private const EMAIL = 'new-user@test.com';

    public function testSuccess(): void
    {
        $this->client->request(
            'POST',
            self::URI,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'id' => self::ID,
                'name' => 'Tester Petrov',
                'email' => self::EMAIL,
                'password' => 'secret',
            ])
        );

        self::assertEquals(201, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = (string) $this->client->getResponse()->getContent());
        self::assertIsArray($data = json_decode($content, true));

        self::assertEquals([], $data);

        self::assertEmailCount(1);

        $email = self::getMailerMessage();
        self::assertNotNull($email);

        self::assertEmailHtmlBodyContains($email, 'Finish your registration');
        self::assertEmailTextBodyContains($email, 'Finish your registration');
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
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'id' => '100001',
                'name' => 'Name',
                'email' => 'email',
                'password' => 'pas',
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
                'password' => 'This value is too short. It should have 6 characters or more.',
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
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'id' => UserFixture::ID,
                'name' => 'Tester Petrov',
                'email' => self::EMAIL,
                'password' => 'secret',
            ])
        );

        self::assertEquals(409, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = (string) $this->client->getResponse()->getContent());
        self::assertIsArray($data = json_decode($content, true));

        self::assertArraySubset(['message' => 'User already exists'], $data);
    }

    /**
     * @throws \Exception
     */
    public function testDuplicatedEmail(): void
    {
        $this->client->request(
            'POST',
            self::URI,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'id' => self::ID,
                'name' => 'Tester Petrov',
                'email' => UserFixture::IDENTIFIER,
                'password' => 'secret',
            ])
        );

        self::assertEquals(409, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = (string) $this->client->getResponse()->getContent());
        self::assertIsArray($data = json_decode($content, true));

        self::assertArraySubset(['message' => 'User already exists'], $data);
    }
}
