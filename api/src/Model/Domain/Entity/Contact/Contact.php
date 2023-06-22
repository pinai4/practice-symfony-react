<?php

declare(strict_types=1);

namespace App\Model\Domain\Entity\Contact;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

/**
 * @ORM\Entity()
 * @ORM\Table(name="domain_contacts")
 */
class Contact
{
    /**
     * @ORM\Column(type="uuid")
     * @ORM\Id()
     */
    private UuidV4 $id;

    /**
     * @ORM\Column(type="uuid")
     */
    private UuidV4 $ownerId;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $crDate;

    /**
     * @ORM\Embedded(class="Name")
     */
    private Name $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $organization = null;

    /**
     * @ORM\Column(type="domain_contact_email")
     */
    private Email $email;

    /**
     * @ORM\Embedded(class="Phone")
     */
    private Phone $phone;

    /**
     * @ORM\Column(type="string")
     */
    private string $address;

    /**
     * @ORM\Column(type="string")
     */
    private string $city;

    /**
     * @ORM\Column(type="string")
     */
    private string $state;

    /**
     * @ORM\Column(type="string")
     */
    private string $zip;

    /**
     * @ORM\Column(type="string")
     */
    private string $country;

    public function __construct(
        UuidV4 $id,
        UuidV4 $ownerId,
        DateTimeImmutable $crDate,
        Name $name,
        ?string $organization,
        Email $email,
        Phone $phone,
        string $address,
        string $city,
        string $state,
        string $zip,
        string $country
    ) {
        $this->id = $id;
        $this->ownerId = $ownerId;
        $this->crDate = $crDate;
        $this->name = $name;
        $this->organization = $organization;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
        $this->city = $city;
        $this->state = $state;
        $this->zip = $zip;
        $this->country = $country;
    }

    public static function create(
        UuidV4 $id,
        UuidV4 $ownerId,
        Name $name,
        ?string $organization,
        Email $email,
        Phone $phone,
        string $address,
        string $city,
        string $state,
        string $zip,
        string $country
    ): self {
        return new self(
            $id,
            $ownerId,
            new DateTimeImmutable(),
            $name,
            $organization,
            $email,
            $phone,
            $address,
            $city,
            $state,
            $zip,
            $country
        );
    }

}