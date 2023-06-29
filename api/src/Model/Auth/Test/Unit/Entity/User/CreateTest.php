<?php

declare(strict_types=1);

namespace App\Model\Auth\Test\Unit\Entity\User;

use App\Model\Auth\Entity\User\Email;
use App\Model\Auth\Entity\User\Role;
use App\Model\Auth\Entity\User\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = User::register(
            $id = Uuid::v4(),
            $name = 'Tester Ivanov',
            $email = new Email('test@test.com'),
            $password = 'hash',
            $date = new \DateTimeImmutable()
        );

        self::assertEquals($id, $user->getId());
        self::assertEquals($name, $user->getName());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($date, $user->getDate());
        self::assertEquals($password, $user->getPassword());

        self::assertEquals(Role::USER, $user->getRole()->getName());
    }
}
