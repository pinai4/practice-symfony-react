<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Contact;

use App\Tests\Functional\AuthWebTestCase;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;

class ShowTest extends AuthWebTestCase
{
    use ArraySubsetAsserts;

    private const URI = '/api/contacts';
    private const WRONG_CONTACT_ID = '11111111-1111-4111-1111-111111111111';

    public function testGuest(): void
    {
        $this->client->request('GET', self::URI.'/'.ContactFixture::ID);

        $response = $this->client->getResponse();

        $this->assertSame(401, $response->getStatusCode());
    }

    public function testPost(): void
    {
        $this->client->request('POST', self::URI.'/'.ContactFixture::ID, [], [], [
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->getEncodedAccessToken()),
        ]);

        $response = $this->client->getResponse();

        $this->assertSame(405, $response->getStatusCode());
    }

    public function testWrongId(): void
    {
        $this->client->request('GET', self::URI.'/'.self::WRONG_CONTACT_ID, [], [], [
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
        $this->client->request('GET', self::URI.'/'.ContactFixture::ID, [], [], [
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->getEncodedAccessToken()),
        ]);

        $response = $this->client->getResponse();

        $this->assertSame(200, $response->getStatusCode());

        self::assertJson($content = (string) $this->client->getResponse()->getContent());

        self::assertIsArray($data = json_decode($content, true));

        self::assertArrayHasKey('cr_date', $data);

        self::assertArraySubset([
            'id' => ContactFixture::ID,
            'name' => ContactFixture::FIRST_NAME.' '.ContactFixture::LAST_NAME,
            'organization' => null,
            'email' => ContactFixture::EMAIL,
            'phone' => '+'.ContactFixture::PHONE_COUNTRY_CODE.'.'.ContactFixture::PHONE_NUMBER,
            'address' => ContactFixture::ADDRESS,
            'city' => ContactFixture::CITY,
            'state' => ContactFixture::STATE,
            'zip' => ContactFixture::ZIP,
            'country' => ContactFixture::COUNTRY,
        ], $data);
    }
}
