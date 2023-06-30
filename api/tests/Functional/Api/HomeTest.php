<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Tests\Functional\AuthWebTestCase;

class HomeTest extends AuthWebTestCase
{
    public function testGet(): void
    {
        $this->client->request('GET', '/api/');

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = (string) $this->client->getResponse()->getContent());

        self::assertIsArray($data = json_decode($content, true));
        self::assertEquals([
            'name' => 'JSON API',
        ], $data);
    }

    public function testPost(): void
    {
        $this->client->request('POST', '/api/');

        self::assertEquals(405, $this->client->getResponse()->getStatusCode());
    }
}
