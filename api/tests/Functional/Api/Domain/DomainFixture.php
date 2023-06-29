<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Domain;

use App\Model\Auth\Entity\User\User;
use App\Model\Domain\Entity\Contact\Contact;
use App\Model\Domain\Test\Builder\DomainBuilder;
use App\Tests\Functional\Api\Contact\ContactFixture;
use App\Tests\Functional\UserFixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV4;

class DomainFixture extends Fixture implements DependentFixtureInterface
{
    public const ID = '1597f5e8-c0da-475a-b97a-6ed7c9401e64';
    public const NAME = 'test-domain.com';
    public const PERIOD = 5;

    public function load(ObjectManager $manager)
    {
        /**
         * @var $user User
         */
        $user = $this->getReference(UserFixture::REFERENCE);

        /** @var Contact $contact */
        $contact = $this->getReference(ContactFixture::REFERENCE);

        $domain = (new DomainBuilder())
            ->withId(new UuidV4(self::ID))
            ->withOwnerId($user->getId())
            ->withName(self::NAME)
            ->withPeriod(self::PERIOD)
            ->withOwnerContact($contact)
            ->build();

        $manager->persist($domain);

        for ($i = 1; $i < 11; ++$i) {
            $domain = (new DomainBuilder())
                ->withId(new UuidV4())
                ->withOwnerId($user->getId())
                ->withName('test-auto-domain'.$i.'.com')
                ->withPeriod(rand(1, 10))
                ->withOwnerContact($contact)
                ->build();

            $manager->persist($domain);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
            ContactFixture::class,
        ];
    }
}
