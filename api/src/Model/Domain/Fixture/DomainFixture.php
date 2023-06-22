<?php

namespace App\Model\Domain\Fixture;

use App\Model\Domain\Entity\Contact\Contact;
use App\Model\Domain\Entity\Domain\Domain;
use App\Model\Auth\Entity\User\User;
use App\Model\Auth\Fixture\UserFixture;
use DateInterval;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use Symfony\Component\Uid\UuidV4;

class DomainFixture extends Fixture implements DependentFixtureInterface
{
    public const DOMAIN_REFERENCE = 'domain-reference';

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        /** @var User $user */
        $user = $this->getReference(UserFixture::USER_REFERENCE);

        /** @var Contact $contact */
        $contact = $this->getReference(ContactFixture::CONTACT_REFERENCE);

        $domain = new Domain(
            new UuidV4(),
            $user->getId(),
            'domain-example.com',
            new DateTimeImmutable('2021-11-21 01:00:00'),
            new DateTimeImmutable('2023-11-21 01:00:00'),
            $contact
        );

        $manager->persist($domain);

        $this->addReference(self::DOMAIN_REFERENCE, $domain);

        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $crDate = new DateTimeImmutable($faker->dateTimeThisDecade()->format('Y-m-d H:i:s'));
            $expDate = $crDate->add(new DateInterval('P' . $faker->numberBetween(1, 10) . 'Y'));

            $domain = new Domain(
                new UuidV4(),
                $user->getId(),
                $faker->domainName(),
                $crDate,
                $expDate,
                $contact
            );
            $manager->persist($domain);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
            ContactFixture::class
        ];
    }
}