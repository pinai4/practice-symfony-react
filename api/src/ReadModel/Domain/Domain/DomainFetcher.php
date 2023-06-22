<?php

declare(strict_types=1);

namespace App\ReadModel\Domain\Domain;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use UnexpectedValueException;

class DomainFetcher
{
    private Connection $connection;
    private PaginatorInterface $paginator;

    public function __construct(Connection $connection, PaginatorInterface $paginator)
    {
        $this->connection = $connection;
        $this->paginator = $paginator;
    }

    /**
     * @throws Exception
     */
    public function findByIdAndOwner(string $id, string $ownerId): ?DomainView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'owner_id',
                'name',
                'cr_date',
                'exp_date'
            )
            ->from('domain_domains')
            ->where('id = :id')
            ->andWhere('owner_id = :ownerId')
            ->setParameter('id', $id)
            ->setParameter('ownerId', $ownerId)
            ->executeQuery();

        $row = $stmt->fetchAssociative();

        if ($row === false) {
            return null;
        }

        return new DomainView($row['id'], $row['owner_id'], $row['name'], $row['cr_date'], $row['exp_date']);
    }

    /**
     * @throws Exception
     */
    public function findByNameAndOwner(string $name, string $ownerId): ?DomainView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'owner_id',
                'name',
                'cr_date',
                'exp_date'
            )
            ->from('domain_domains')
            ->where('name = :name')
            ->andWhere('owner_id = :ownerId')
            ->setParameter('name', $name)
            ->setParameter('ownerId', $ownerId)
            ->executeQuery();

        $row = $stmt->fetchAssociative();

        if ($row === false) {
            return null;
        }

        return new DomainView($row['id'], $row['owner_id'], $row['name'], $row['cr_date'], $row['exp_date']);
    }

    /**
     * @throws Exception
     */
    public function all(
        string $ownerId,
        DomainFilter $filter,
        int $page,
        int $limit,
        ?string $sort,
        ?string $direction
    ): PaginationInterface {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'd.id',
                'd.name',
                'd.cr_date',
                'd.exp_date'
            )
            ->from('domain_domains', 'd')
            ->where('owner_id = :ownerId')
            ->setParameter('ownerId', $ownerId);

        if (!empty($filter->name)) {
            $qb->andWhere($qb->expr()->like('d.name', ':name'));
            $qb->setParameter('name', '%' . $filter->name . '%');
        }

        if (!empty($sort)) {
            if (!in_array(
                $sort,
                [
                    'id',
                    'name',
                    'cr_date',
                    'exp_date',
                ],
                true
            )) {
                throw new UnexpectedValueException('Cannot sort by ' . $sort);
            }
            $qb->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');
        }

        $pagination = $this->paginator->paginate($qb, $page, $limit);

        $domains = (array)$pagination->getItems();

        $contacts = $this->batchLoadContacts(array_column($domains, 'id'));

        $pagination->setItems(
            array_map(static function (array $domain) use ($contacts) {
                $domainContacts = array_values(
                    array_filter($contacts, static function (array $contact) use ($domain) {
                        return $contact['domain_id'] === $domain['id'];
                    })
                );
                $domainContacts = array_map(static function (array $domainContact) {
                    return [
                        'type' => $domainContact['type'],
                        'id' => $domainContact['contact_id'],
                    ];
                }, $domainContacts);
                return array_merge($domain, [
                    'contacts' => $domainContacts,
                ]);
            }, $domains)
        );

        return $pagination;
    }

    /**
     * @throws Exception
     */
    private function batchLoadContacts(array $ids): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'dc.type',
                'dc.domain_id',
                'dc.contact_id',
            )
            ->from('domain_domain_linked_contacts', 'dc')
            ->where('dc.domain_id IN (:ids)')
            ->setParameter('ids', $ids, Connection::PARAM_STR_ARRAY)
            ->executeQuery();

        return $stmt->fetchAllAssociative();
    }
}
