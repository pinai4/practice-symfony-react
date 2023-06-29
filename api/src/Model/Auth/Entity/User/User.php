<?php

namespace App\Model\Auth\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_users", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="email_idx", columns={"email"})
 * })
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private UuidV4 $id;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Column(type="user_user_email")
     */
    private Email $email;

    /**
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $date;

    /**
     * @ORM\Column(type="user_user_role")
     */
    private Role $role;

    private function __construct(UuidV4 $id, string $name, Email $email, string $password, \DateTimeImmutable $date, Role $role)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->date = $date;
        $this->role = $role;
    }

    public static function register(UuidV4 $id, string $name, Email $email, string $password, \DateTimeImmutable $date): self
    {
        return new self($id, $name, $email, $password, $date, Role::user());
    }

    public static function registerAdmin(UuidV4 $id, string $name, Email $email, string $password, \DateTimeImmutable $date): self
    {
        return new self($id, $name, $email, $password, $date, Role::admin());
    }

    public function getId(): UuidV4
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getRole(): Role
    {
        return $this->role;
    }
}
