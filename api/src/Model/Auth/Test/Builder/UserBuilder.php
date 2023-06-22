<?php

declare(strict_types=1);

namespace App\Model\Auth\Test\Builder;

use App\Model\Auth\Entity\User\Email;
use App\Model\Auth\Entity\User\User;
use DateTimeImmutable;
use Symfony\Component\Uid\UuidV4;

final class UserBuilder
{
    private UuidV4 $id;
    private string $name;
    private Email $email;
    private string $password;
    private DateTimeImmutable $date;

    public function __construct()
    {
        $this->id = new UuidV4();
        $this->name = 'Test User Name';
        $this->email = new Email('mail@example.com');
        $this->password = 'hash';
        $this->date = new DateTimeImmutable();
    }

    public function withId(UuidV4 $id): self
    {
        $clone = clone $this;
        $clone->id = $id;
        return $clone;
    }

    public function withName(string $name): self
    {
        $clone = clone $this;
        $clone->name = $name;
        return $clone;
    }

    public function withEmail(Email $email): self
    {
        $clone = clone $this;
        $clone->email = $email;
        return $clone;
    }

    public function withPassword(string $password): self
    {
        $clone = clone $this;
        $clone->password = $password;
        return $clone;
    }

    public function build(): User
    {
        return User::register(
            $this->id,
            $this->name,
            $this->email,
            $this->password,
            $this->date
        );
    }
}
