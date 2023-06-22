<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Contact;

use App\Model\Domain\Entity\Contact\Contact;
use App\Model\Domain\Entity\Contact\Email;
use App\Model\Domain\Entity\Contact\Name;
use App\Model\Domain\Entity\Contact\Phone;
use App\Model\Auth\Entity\User\User;
use App\Tests\Functional\UserFixture;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV4;

class ContactFixture extends Fixture implements DependentFixtureInterface
{
    public const ID = '30cbad32-de9a-4c5e-a1ed-ae9957849af7';
    public const CR_DATE = '2021-12-05 23:59:59';
    public const FIRST_NAME = 'Tester';
    public const LAST_NAME = 'Petrov';
    public const EMAIL = 'test-default@email.com';
    public const PHONE_COUNTRY_CODE = 1;
    public const PHONE_NUMBER = 12312323;
    public const ADDRESS = 'Default Street';
    public const CITY = 'Kyiv';
    public const STATE = 'Kyivskaya oblast';
    public const ZIP = '10001';
    public const COUNTRY = 'UA';

    public const REFERENCE = 'test-contact-reference';

    public function load(ObjectManager $manager)
    {
        /**
         * @var $user User
         */
        $user = $this->getReference(UserFixture::REFERENCE);

        $contact = new Contact(
            new UuidV4(self::ID),
            $user->getId(),
            new DateTimeImmutable(self::CR_DATE),
            new Name(self::FIRST_NAME, self::LAST_NAME),
            null,
            new Email(self::EMAIL),
            new Phone(self::PHONE_COUNTRY_CODE, self::PHONE_NUMBER),
            self::ADDRESS,
            self::CITY,
            self::STATE,
            self::ZIP,
            self::COUNTRY
        );

        $manager->persist($contact);

        $this->addReference(self::REFERENCE, $contact);

        for ($i=1;$i<11;$i++) {
            $contact = new Contact(
                new UuidV4(),
                $user->getId(),
                new DateTimeImmutable(),
                new Name($i.' List-Tester', 'Surname'),
                $i.' Company List',
                new Email($i.'some-email@example.com'),
                new Phone($i, 1000000+$i),
                $i.' Street',
                $i.' Kyiv',
                $i.' Kyivska oblast',
                $i.$i.$i.$i.$i.$i,
                ((bool)($i%2))?'UA':'RU'
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