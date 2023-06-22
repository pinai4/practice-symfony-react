<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Contact;

use App\Tests\Functional\AuthWebTestCase;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;

class IndexTest extends AuthWebTestCase
{
    use ArraySubsetAsserts;

    private const URI = '/api/contacts';

    public function testGuest()
    {
        $this->client->request('GET', self::URI, [], [], ['CONTENT_TYPE' => 'application/json']);

        $response = $this->client->getResponse();

        $this->assertSame(401, $response->getStatusCode());
    }

    public function testSuccess()
    {
        $this->client->request(
            'GET',
            self::URI,
            [
                'filter' => [
                    'name' => ContactFixture::FIRST_NAME . ' ' . ContactFixture::LAST_NAME,
                    'email' => ContactFixture::EMAIL,
                    'phone' => '+' . ContactFixture::PHONE_COUNTRY_CODE . '.' . ContactFixture::PHONE_NUMBER,
                    'address' => ContactFixture::ADDRESS,
                    'city' => ContactFixture::CITY,
                    'state' => ContactFixture::STATE,
                    'zip' => ContactFixture::ZIP,
                    'country' => ContactFixture::COUNTRY,
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
                                       'id' => ContactFixture::ID,
                                       'cr_date' => ContactFixture::CR_DATE,
                                       'name' => ContactFixture::FIRST_NAME . ' ' . ContactFixture::LAST_NAME,
                                       'organization' => null,
                                       'email' => ContactFixture::EMAIL,
                                       'phone' => '+' . ContactFixture::PHONE_COUNTRY_CODE . '.' . ContactFixture::PHONE_NUMBER,
                                       'address' => ContactFixture::ADDRESS,
                                       'city' => ContactFixture::CITY,
                                       'state' => ContactFixture::STATE,
                                       'zip' => ContactFixture::ZIP,
                                       'country' => ContactFixture::COUNTRY,
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
                ['country' => 'UA'],
                'name',
                'asc',
                2,
                2,
                ['total' => 6, 'pages' => 3],
                ['country' => 'UA', 'name' => '5 List-Tester Surname']
            ],
            [
                ['name' => 'List-Tester'],
                'phone',
                'desc',
                3,
                3,
                ['total' => 10, 'pages' => 4],
                ['name' => '3 List-Tester Surname']
            ],
            [
                ['phone' => '+4.1000004'],
                'phone',
                'desc',
                1,
                5,
                ['total' => 1, 'pages' => 1],
                ['organization' => '4 Company List']
            ],
        ];
    }
}