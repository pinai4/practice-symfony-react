<?php

declare(strict_types=1);

namespace App\Model\Domain\Test\Builder;

use App\Model\Domain\Entity\Contact\Contact;
use App\Model\Domain\Entity\Contact\Email;
use App\Model\Domain\Entity\Contact\Name;
use App\Model\Domain\Entity\Contact\Phone;
use Symfony\Component\Uid\UuidV4;

class ContactBuilder
{
    private UuidV4 $id;
    private UuidV4 $ownerId;
    private Name $name;
    private ?string $organization = null;
    private Email $email;
    private Phone $phone;
    private string $address;
    private string $city;
    private string $state;
    private string $zip;
    private string $country;

    public function __construct()
    {
        $this->id = new UuidV4();
        $this->ownerId = new UuidV4();
        $this->name = new Name('Tester', 'Petrov');

        $this->email = new Email('test-default@email.com');
        $this->phone = new Phone(1, 12312323);
        $this->address = 'Default Street';
        $this->city = 'Kyiv';
        $this->state = 'Kyivskaya oblast';
        $this->zip = '10001';
        $this->country = 'UA';
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

    public function withName(Name $name): self
    {
        $clone = clone $this;
        $clone->name = $name;

        return clone $clone;
    }

    public function withEmail(Email $email): self
    {
        $clone = clone $this;
        $clone->email = $email;

        return clone $clone;
    }

    public function withPhone(Phone $phone): self
    {
        $clone = clone $this;
        $clone->phone = $phone;

        return clone $clone;
    }

    public function withAddress(string $address): self
    {
        $clone = clone $this;
        $clone->address = $address;

        return clone $clone;
    }

    public function withCity(string $city): self
    {
        $clone = clone $this;
        $clone->city = $city;

        return clone $clone;
    }

    public function withState(string $state): self
    {
        $clone = clone $this;
        $clone->state = $state;

        return clone $clone;
    }

    public function withZip(string $zip): self
    {
        $clone = clone $this;
        $clone->zip = $zip;

        return clone $clone;
    }

    public function withCountry(string $country): self
    {
        $clone = clone $this;
        $clone->country = $country;

        return clone $clone;
    }

    public function build(): Contact
    {
        return Contact::create(
            $this->id,
            $this->ownerId,
            $this->name,
            $this->organization,
            $this->email,
            $this->phone,
            $this->address,
            $this->city,
            $this->state,
            $this->zip,
            $this->country
        );
    }
}
