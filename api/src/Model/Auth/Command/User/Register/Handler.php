<?php

declare(strict_types=1);

namespace App\Model\Auth\Command\User\Register;

use App\Model\Auth\Entity\User\Email;
use App\Model\Auth\Entity\User\User;
use App\Model\Auth\Entity\User\UserRepository;
use App\Model\Auth\Service\PasswordHasher;
use App\Model\Auth\Service\RegisterSenderInterface;
use App\Model\Flusher;
use DateTimeImmutable;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use DomainException;
use Symfony\Component\Uid\UuidV4;

class Handler
{
    private Flusher $flusher;
    private UserRepository $repo;
    private PasswordHasher $passwordHasher;
    private RegisterSenderInterface $sender;


    public function __construct(
        UserRepository $repo,
        Flusher $flusher,
        PasswordHasher $passwordHasher,
        RegisterSenderInterface $sender
    )
    {
        $this->repo = $repo;
        $this->flusher = $flusher;
        $this->passwordHasher = $passwordHasher;
        $this->sender = $sender;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function handle(Command $command): void
    {
        if ($this->repo->hasById(new UuidV4($command->id))) {
            throw new DomainException('User already exists');
        }

        if ($this->repo->hasByEmail(new Email($command->email))) {
            throw new DomainException('User already exists');
        }

        $user = User::register(
            new UuidV4($command->id),
            $command->name,
            $email = new Email($command->email),
            $this->passwordHasher->hash($command->password),
            new DateTimeImmutable()
        );

        $this->repo->add($user);

        $this->flusher->flush();

        $this->sender->send($email);
    }
}