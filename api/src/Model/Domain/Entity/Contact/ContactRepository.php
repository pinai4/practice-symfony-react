<?php

declare(strict_types=1);

namespace App\Model\Domain\Entity\Contact;

use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Uid\UuidV4;

class ContactRepository
{
    private EntityManagerInterface $em;
    private EntityRepository $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Contact::class);
    }

    public function get(UuidV4 $id): Contact
    {
        if (!$contact = $this->repo->find($id)) {
            throw new EntityNotFoundException('Contact was not found.');
        }
        return $contact;
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
                ->setParameter('id', $id)
                ->getQuery()->getSingleScalarResult() > 0;
    }

    public function add(Contact $contact): void
    {
        $this->em->persist($contact);
    }
}