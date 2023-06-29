<?php

namespace App\Model\Auth\Fixture;

use App\Model\Auth\Entity\User\Email;
use App\Model\Auth\Entity\User\User;
use App\Model\Auth\Service\PasswordHasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV4;

class UserFixture extends Fixture
{
    public const USER_REFERENCE = 'user-reference';

    private PasswordHasher $passwordHasher;

    public function __construct(PasswordHasher $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = User::register(
            new UuidV4(),
            'Sergey User',
            new Email('user@test.com'),
            $this->passwordHasher->hash('secret'),
            new \DateTimeImmutable()
        );
        $manager->persist($user);

        $admin = User::registerAdmin(
            new UuidV4(),
            'Sergey Admin',
            new Email('admin@test.com'),
            $this->passwordHasher->hash('secret'),
            new \DateTimeImmutable()
        );
        $manager->persist($admin);

        $manager->flush();

        $this->addReference(self::USER_REFERENCE, $user);
    }
}
