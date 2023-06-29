<?php

declare(strict_types=1);

namespace App\ReadModel\Domain\Contact;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class ContactFetcher
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
    public function findByIdAndOwner(string $id, string $ownerId): ?ContactView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select([
                'c.id',
                'c.owner_id',
                'c.cr_date',
                'TRIM(CONCAT(c.name_first, \' \', c.name_last)) AS name',
                'c.organization',
                'c.email',
                'TRIM(CONCAT(\'+\', c.phone_country_code, \'.\', c.phone_number)) AS phone',
                'c.address',
                'c.city',
                'c.state',
                'c.zip',
                'c.country',
            ])
            ->from('domain_contacts', 'c')
            ->where('c.id = :id')
            ->andWhere('c.owner_id = :ownerId')
            ->setParameter('id', $id)
            ->setParameter('ownerId', $ownerId)
            ->executeQuery();

        /** @var false|array{organization: ?string}&array<string, string> $row */
        $row = $stmt->fetchAssociative();

        if ($row === false) {
            return null;
        }

        return new ContactView(
            $row['id'],
            $row['owner_id'],
            $row['cr_date'],
            $row['name'],
            $row['organization'],
            $row['email'],
            $row['phone'],
            $row['address'],
            $row['city'],
            $row['state'],
            $row['zip'],
            $row['country']
        );
    }

    public function all(
        string $ownerId,
        ContactFilter $filter,
        int $page,
        int $limit,
        ?string $sort,
        ?string $direction
    ): PaginationInterface {
        $qb = $this->connection->createQueryBuilder()
            ->select([
                'c.id',
                'c.cr_date',
                'TRIM(CONCAT(c.name_first, \' \', c.name_last)) AS name',
                'c.organization',
                'c.email',
                'TRIM(CONCAT(\'+\', c.phone_country_code, \'.\', c.phone_number)) AS phone',
                'c.address',
                'c.city',
                'c.state',
                'c.zip',
                'c.country',
            ])
            ->from('domain_contacts', 'c')
            ->where('owner_id = :ownerId')
            ->setParameter('ownerId', $ownerId);

        if (!empty($filter->name)) {
            $qb->andWhere($qb->expr()->like('TRIM(CONCAT(c.name_first, \' \', c.name_last))', ':name'));
            $qb->setParameter('name', '%'.$filter->name.'%');
        }

        if (!empty($filter->organization)) {
            $qb->andWhere($qb->expr()->like('c.organization', ':organization'));
            $qb->setParameter('organization', '%'.$filter->organization.'%');
        }

        if (!empty($filter->email)) {
            $qb->andWhere($qb->expr()->like('c.email', ':email'));
            $qb->setParameter('email', '%'.$filter->email.'%');
        }

        if (!empty($filter->phone)) {
            $qb->andWhere(
                $qb->expr()->like('TRIM(CONCAT(\'+\', c.phone_country_code, \'.\', c.phone_number))', ':phone')
            );
            $qb->setParameter('phone', '%'.$filter->phone.'%');
        }

        if (!empty($filter->address)) {
            $qb->andWhere($qb->expr()->like('c.address', ':address'));
            $qb->setParameter('address', '%'.$filter->address.'%');
        }

        if (!empty($filter->city)) {
            $qb->andWhere($qb->expr()->like('c.city', ':city'));
            $qb->setParameter('city', '%'.$filter->city.'%');
        }

        if (!empty($filter->state)) {
            $qb->andWhere($qb->expr()->like('c.state', ':state'));
            $qb->setParameter('state', '%'.$filter->state.'%');
        }

        if (!empty($filter->zip)) {
            $qb->andWhere($qb->expr()->like('c.zip', ':zip'));
            $qb->setParameter('zip', '%'.$filter->zip.'%');
        }

        if (!empty($filter->country)) {
            $qb->andWhere($qb->expr()->like('c.country', ':country'));
            $qb->setParameter('country', '%'.$filter->country.'%');
        }

        if (!empty($sort)) {
            if (!in_array(
                $sort,
                [
                    'id',
                    'cr_date',
                    'name',
                    'organization',
                    'email',
                    'phone',
                    'address',
                    'city',
                    'state',
                    'zip',
                    'country',
                ],
                true
            )) {
                throw new \UnexpectedValueException('Cannot sort by '.$sort);
            }
            $qb->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');
        }

        return $this->paginator->paginate($qb, $page, $limit);
    }
}
