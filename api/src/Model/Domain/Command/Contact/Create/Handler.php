<?php

declare(strict_types=1);

namespace App\Model\Domain\Command\Contact\Create;

use App\Model\Domain\Entity\Contact\Contact;
use App\Model\Domain\Entity\Contact\ContactRepository;
use App\Model\Domain\Entity\Contact\Email;
use App\Model\Domain\Entity\Contact\Name;
use App\Model\Domain\Entity\Contact\Phone;
use App\Model\Flusher;
use DomainException;
use Symfony\Component\Uid\UuidV4;

class Handler
{
    private ContactRepository $repo;
    private Flusher $flusher;

    public function __construct(ContactRepository $repo, Flusher $flusher)
    {
        $this->repo = $repo;
        $this->flusher = $flusher;
    }

    public function handle(Command $command)
    {
        if ($this->repo->hasById(new UuidV4($command->id))) {
            throw new DomainException('Contact already exists');
        }

        $arrName = explode(' ', $command->name);
        $firstName = array_shift($arrName);
        $secondName = implode(' ', $arrName);

        $phone = str_replace('+','',$command->phone);
        $arrPhone = explode('.', $phone);
        $phoneCountryCode = (int)$arrPhone[0];
        $phoneNumber = (int)$arrPhone[1];

        $domain = Contact::create(
            new UuidV4($command->id),
            new UuidV4($command->ownerId),
            new Name($firstName, $secondName),
            $command->organization,
            new Email($command->email),
            new Phone($phoneCountryCode, $phoneNumber),
            $command->address,
            $command->city,
            $command->state,
            $command->zip,
            $command->country
        );

        $this->repo->add($domain);

        $this->flusher->flush();
    }
}