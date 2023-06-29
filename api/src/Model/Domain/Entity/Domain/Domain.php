<?php

declare(strict_types=1);

namespace App\Model\Domain\Entity\Domain;

use App\Model\Domain\Entity\Contact\Contact;
use App\Model\Domain\Entity\Domain\LinkedContact\LinkedContact;
use App\Model\Domain\Entity\Domain\LinkedContact\Type;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

/**
 * @ORM\Entity()
 * @ORM\Table(name="domain_domains")
 */
class Domain
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
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $cr_date;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $exp_date;

    /**
     * @var Collection<array-key,LinkedContact>
     *
     * @ORM\OneToMany(targetEntity="App\Model\Domain\Entity\Domain\LinkedContact\LinkedContact", mappedBy="domain", cascade={"all"}, orphanRemoval=true)
     */
    private Collection $linkedContacts;

    public function __construct(
        UuidV4 $id,
        UuidV4 $ownerId,
        string $name,
        \DateTimeImmutable $cr_date,
        \DateTimeImmutable $exp_date,
        Contact $ownerContact
    ) {
        $this->id = $id;
        $this->ownerId = $ownerId;
        $this->name = $name;
        $this->cr_date = $cr_date;
        $this->exp_date = $exp_date;

        $this->linkedContacts = new ArrayCollection();
        $this->linkedContacts->add(new LinkedContact(new UuidV4(), new Type(Type::OWNER), $this, $ownerContact));
    }

    public static function create(UuidV4 $id, UuidV4 $ownerId, string $name, int $period, Contact $ownerContact): self
    {
        if ($period <= 0) {
            throw new \DomainException('Period can not be negative or zero value');
        }

        return new self(
            $id,
            $ownerId,
            $name,
            new \DateTimeImmutable(),
            new \DateTimeImmutable("+{$period} year"),
            $ownerContact
        );
    }
}
