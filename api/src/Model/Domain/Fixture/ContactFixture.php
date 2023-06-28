<?php

namespace App\Model\Domain\Fixture;

use App\Model\Domain\Entity\Contact\Contact;
use App\Model\Domain\Entity\Contact\Email;
use App\Model\Domain\Entity\Contact\Name;
use App\Model\Domain\Entity\Contact\Phone;
use App\Model\Auth\Entity\User\User;
use App\Model\Auth\Fixture\UserFixture;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use Symfony\Component\Uid\UuidV4;

class ContactFixture extends Fixture implements DependentFixtureInterface
{
    public const CONTACT_REFERENCE = 'contact-reference';

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        /** @var User $user */
        $user = $this->getReference(UserFixture::USER_REFERENCE);

        $contact = new Contact(
            new UuidV4(),
            $user->getId(),
            new DateTimeImmutable(),
            new Name('Vasya', 'Petrov'),
            'My Company',
            new Email('some-email@example.com'),
            new Phone(1, 202020202),
            'Street 2',
            'Kyiv',
            'Kyivska oblast',
            '111101',
            'UA'
        );

        $manager->persist($contact);

        $this->addReference(self::CONTACT_REFERENCE, $contact);

        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $contact = new Contact(
                new UuidV4(),
                $user->getId(),
                new DateTimeImmutable($faker->dateTimeThisDecade()->format('Y-m-d H:i:s')),
                new Name($faker->firstName(), $faker->lastName()),
                ($faker->boolean() ? $faker->company() : null),
                new Email($faker->email()),
                new Phone($faker->numberBetween(1, 999), $faker->numberBetween(9999999, 99999999)),
                $faker->streetAddress(),
                $faker->city(),
                $faker->state(),
                $faker->postcode(),
                $faker->countryCode()
            );
            $manager->persist($contact);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class
        ];
    }
}