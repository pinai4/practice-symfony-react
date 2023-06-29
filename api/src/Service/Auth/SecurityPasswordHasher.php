<?php

declare(strict_types=1);

namespace App\Service\Auth;

use App\Model\Auth\Service\PasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class SecurityPasswordHasher implements PasswordHasher
{
    private PasswordHasherInterface $decoratedPasswordHasher;

    public function __construct(PasswordHasherFactoryInterface $passwordHasherFactory)
    {
        $this->decoratedPasswordHasher = $passwordHasherFactory->getPasswordHasher(
            PasswordAuthenticatedUserInterface::class
        );
    }

    public function hash(string $plainPassword): string
    {
        return $this->decoratedPasswordHasher->hash($plainPassword);
    }

    public function verify(string $hashedPassword, string $plainPassword): bool
    {
        return $this->decoratedPasswordHasher->verify($hashedPassword, $plainPassword);
    }
}
