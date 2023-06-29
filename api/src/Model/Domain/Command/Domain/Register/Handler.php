<?php

declare(strict_types=1);

namespace App\Model\Domain\Command\Domain\Register;

use App\Model\Domain\Entity\Contact\ContactRepository;
use App\Model\Domain\Entity\Domain\Domain;
use App\Model\Domain\Entity\Domain\DomainRepository;
use App\Model\Flusher;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Uid\UuidV4;

class Handler
{
    private DomainRepository $domains;
    private ContactRepository $contacts;
    private Flusher $flusher;

    public function __construct(DomainRepository $domains, ContactRepository $contacts, Flusher $flusher)
    {
        $this->domains = $domains;
        $this->contacts = $contacts;
        $this->flusher = $flusher;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function handle(Command $command): void
    {
        if ($this->domains->hasById(new UuidV4($command->id))) {
            throw new \DomainException('Domain already exists');
        }

        $name = $command->name;

        if ($this->domains->hasByName($name)) {
            throw new \DomainException('Domain already exists');
        }

        $ownerContact = $this->contacts->get(new UuidV4($command->ownerContactId));

        $domain = Domain::create(
            new UuidV4($command->id),
            new UuidV4($command->ownerId),
            $name,
            $command->period,
            $ownerContact
        );

        $this->domains->add($domain);

        $this->flusher->flush();
    }
}
