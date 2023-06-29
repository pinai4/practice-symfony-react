<?php

declare(strict_types=1);

namespace App\ReadModel\Auth;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class UserFetcher
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     */
    public function findForAuthByEmail(string $email): ?AuthView
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select([
                'id',
                'name',
                'email',
                'password',
                'role',
            ])
            ->from('user_users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->executeQuery();

        /** @var array<string,string>|false $row */
        $row = $stmt->fetchAssociative();

        if ($row === false) {
            return null;
        }

        return new AuthView($row['id'], $row['name'], $row['email'], $row['password'], $row['role']);
    }
}
