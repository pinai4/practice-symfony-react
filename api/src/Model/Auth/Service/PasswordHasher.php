<?php

declare(strict_types=1);

namespace App\Model\Auth\Service;

interface PasswordHasher
{
    public function hash(string $plainPassword): string;

    public function verify(string $hashedPassword, string $plainPassword): bool;
}