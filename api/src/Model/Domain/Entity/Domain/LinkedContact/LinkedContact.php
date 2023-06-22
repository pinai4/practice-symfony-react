<?php

declare(strict_types=1);

namespace App\Model\Domain\Entity\Domain\LinkedContact;

use App\Model\Domain\Entity\Contact\Contact;
use App\Model\Domain\Entity\Domain\Domain;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

/**
 * @ORM\Entity()
 * @ORM\Table(name="domain_domain_linked_contacts", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"type", "domain_id", "contact_id"})
 * })
 */
class LinkedContact
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private UuidV4 $id;

    /**
     * @ORM\Column(type="domain_domain_linked_contact_type")
     */
    private Type $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Model\Domain\Entity\Domain\Domain", inversedBy="linkedContacts")
     * @ORM\JoinColumn(name="domain_id", referencedColumnName="id", nullable=false)
     */
    private Domain $domain;

    /**
     * @ORM\ManyToOne(targetEntity="App\Model\Domain\Entity\Contact\Contact", cascade={"persist"})
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id", nullable=false)
     */
    private Contact $contact;

    public function __construct(UuidV4 $id, Type $type, Domain $domain, Contact $contact)
    {
        $this->id = $id;
        $this->type = $type;
        $this->domain = $domain;
        $this->contact = $contact;
    }
}