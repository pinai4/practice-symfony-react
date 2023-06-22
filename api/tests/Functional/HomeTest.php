<?php

declare(strict_types=1);

namespace App\Tests\Functional;

class HomeTest extends AuthWebTestCase
{
    public function testGuest(): void
    {
        $this->client->request('GET', '/');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('Site home page', $this->client->getResponse()->getContent());
    }
}
