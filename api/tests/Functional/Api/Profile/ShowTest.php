<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Profile;

use App\Tests\Functional\AuthWebTestCase;
use App\Tests\Functional\UserFixture;

class ShowTest extends AuthWebTestCase
{
    private const URI = '/api/profile';

    public function testGuest()
    {
        $this->client->request('GET', self::URI);

        $response = $this->client->getResponse();

        $this->assertSame(401, $response->getStatusCode());
    }

    public function testPost()
    {
        $this->client->request('POST', self::URI, [], [], [
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->getEncodedAccessToken()),
        ]);

        $response = $this->client->getResponse();

        $this->assertSame(405, $response->getStatusCode());
    }

    public function testGet()
    {
        $this->client->request('GET', self::URI, [], [], [
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->getEncodedAccessToken()),
        ]);

        $response = $this->client->getResponse();

        $this->assertSame(200, $response->getStatusCode());

        self::assertJson($content = $this->client->getResponse()->getContent());

        $data = json_decode($content, true);

        self::assertEquals([
                               'name' => UserFixture::NAME,
                           ], $data);
    }
}
