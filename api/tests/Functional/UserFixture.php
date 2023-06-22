<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Model\Auth\Entity\User\Email;
use App\Model\Auth\Test\Builder\UserBuilder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV4;

class UserFixture extends Fixture
{
    public const ID = '0ddf32a4-47d9-4cd7-96e5-bba8a4761a8f';
    public const NAME = 'OAuth Test User';
    public const IDENTIFIER = 'oauth-password-user@app.test';
    public const PASSWORD = '$2y$13$sRn4QLRuIf/Ewd7baCY7Fu1u/WUtEIy8aRDggCioJ9O2/iiBnOalu'; // bcrypt 'password'
    public const PASSWORD_PLAIN = 'password';

    public const REFERENCE = 'test-user-reference';

    public function load(ObjectManager $manager)
    {
        $user = (new UserBuilder())
            ->withId(new UuidV4(self::ID))
            ->withEmail(new Email(self::IDENTIFIER))
            ->withPassword(self::PASSWORD)
            ->withName(self::NAME)
            ->build();

        $manager->persist($user);

        $manager->flush();

        $this->addReference(self::REFERENCE, $user);
    }

}