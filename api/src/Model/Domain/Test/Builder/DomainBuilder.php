<?php

declare(strict_types=1);

namespace App\Model\Domain\Test\Builder;

use App\Model\Domain\Entity\Contact\Contact;
use App\Model\Domain\Entity\Domain\Domain;
use Symfony\Component\Uid\UuidV4;

class DomainBuilder
{
    private UuidV4 $id;
    private UuidV4 $ownerId;
    private string $name;
    private int $period;
    private Contact $ownerContact;

    public function __construct()
    {
        $this->id = new UuidV4();
        $this->ownerId = new UuidV4();
        $this->name = 'test-domain.net';
        $this->period = 1;
        $this->ownerContact = (new ContactBuilder())->withOwnerId($this->ownerId)->build();
    }

    public function withId(UuidV4 $id): self
    {
        $clone = clone $this;
        $clone->id = $id;

        return clone $clone;
    }

    public function withOwnerId(UuidV4 $ownerId): self
    {
        $clone = clone $this;
        $clone->ownerId = $ownerId;

        return clone $clone;
    }

    public function withName(string $name): self
    {
        $clone = clone $this;
        $clone->name = $name;

        return clone $clone;
    }

    public function withPeriod(int $period): self
    {
        $clone = clone $this;
        $clone->period = $period;

        return clone $clone;
    }

    public function withOwnerContact(Contact $contact): self
    {
        $clone = clone $this;
        $clone->ownerContact = $contact;

        return clone $clone;
    }

    public function build(): Domain
    {
        return Domain::create(
            $this->id,
            $this->ownerId,
            $this->name,
            $this->period,
            $this->ownerContact
        );
    }
}
