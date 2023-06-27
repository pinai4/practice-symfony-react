<?php

declare(strict_types=1);

namespace App\Model\Auth\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Uid\UuidV4;

class UserRepository
{
    private EntityManagerInterface $em;
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(User::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function hasByEmail(Email $email): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->where('t.email = :email')
                ->setParameter('email', $email->getValue())
                ->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function hasById(UuidV4 $id): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->where('t.id = :id')
                ->setParameter('id', $id, 'uuid')
                ->getQuery()->getSingleScalarResult() > 0;
    }

    public function add(User $domain): void
    {
        $this->em->persist($domain);
    }
}