<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Domain;

use App\Tests\Functional\Api\Contact\ContactFixture;
use App\Tests\Functional\AuthWebTestCase;
use DateInterval;
use DateTimeImmutable;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;

class IndexTest extends AuthWebTestCase
{
    use ArraySubsetAsserts;

    private const URI = '/api/domains';

    public function testGuest()
    {
        $this->client->request('GET', self::URI, [], [], ['CONTENT_TYPE' => 'application/json']);

        $response = $this->client->getResponse();

        $this->assertSame(401, $response->getStatusCode());
    }

    /**
     * @throws \Exception
     */
    public function testSuccess()
    {
        $this->client->request(
            'GET',
            self::URI,
            [
                'filter' => [
                    'name' => DomainFixture::NAME,
                ],
                'sort' => 'name',
                'direction' => 'asc',
                'page' => '1',
                'per_page' => '10',
            ],
            [],
            [
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->getEncodedAccessToken()),
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = $this->client->getResponse()->getContent());

        $data = json_decode($content, true);

        self::assertIsArray($data);
        self::assertArrayHasKey('items', $data);
        self::assertArrayHasKey('pagination', $data);
        self::assertIsArray($data['items']);
        self::assertIsArray($data['pagination']);

        self::assertCount(1, $data['items']);

        self::assertEquals([
                               'items' => [
                                   [
                                       'id' => DomainFixture::ID,
                                       'name' => DomainFixture::NAME,
                                       'cr_date' => (new DateTimeImmutable($data['items'][0]['cr_date']))->format(
                                           'Y-m-d H:i:s'
                                       ),
                                       'exp_date' => (new DateTimeImmutable($data['items'][0]['cr_date']))
                                           ->add(new DateInterval('P' . DomainFixture::PERIOD . 'Y'))
                                           ->format('Y-m-d H:i:s'),
                                       'contacts' => [
                                           [
                                               'type' => 'owner',
                                               'id' => ContactFixture::ID
                                           ]
                                       ]
                                   ]
                               ],
                               'pagination' => [
                                   'total' => 1,
                                   'count' => 1,
                                   'per_page' => 10,
                                   'page' => 1,
                                   'pages' => 1,
                               ],
                           ], $data);
    }

    /**
     * @dataProvider useCases
     */
    public function testInputParams(
        array $filter,
        string $sort,
        string $direction,
        int $page,
        int $perPage,
        array $expectedResultPagination,
        array $expectedResultFirstItem
    ) {
        $this->client->request(
            'GET',
            self::URI,
            [
                'filter' => $filter,
                'sort' => $sort,
                'direction' => $direction,
                'page' => $page,
                'per_page' => $perPage,
            ],
            [],
            [
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->getEncodedAccessToken()),
                'CONTENT_TYPE' => 'application/json',
            ]
        );

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = $this->client->getResponse()->getContent());

        $data = json_decode($content, true);

        self::assertIsArray($data);

        self::assertArrayHasKey('pagination', $data);
        self::assertIsArray($data['pagination']);
        self::assertArraySubset($expectedResultPagination, $data['pagination']);

        self::assertArrayHasKey('items', $data);
        self::assertIsArray($data['items']);
        self::assertArrayHasKey(0, $data['items']);
        self::assertIsArray($data['items'][0]);
        self::assertArraySubset($expectedResultFirstItem, $data['items'][0]);
    }

    public function useCases(): array
    {
        return [
            [
                ['name' => 'test-auto-domain'],
                'name',
                'asc',
                2,
                2,
                ['total' => 10, 'pages' => 5],
                ['name' => 'test-auto-domain2.com']
            ],
            [
                ['name' => 'test-auto-domain1'],
                'name',
                'desc',
                2,
                1,
                ['total' => 2, 'pages' => 2],
                ['name' => 'test-auto-domain1.com']
            ],
            [
                ['name' => 'test-auto-domain2'],
                '',
                '',
                1,
                5,
                ['total' => 1, 'pages' => 1],
                ['name' => 'test-auto-domain2.com']
            ],
        ];
    }
}