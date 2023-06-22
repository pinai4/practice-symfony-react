<?php

declare(strict_types=1);

namespace App\Model\Domain\Entity\Domain\LinkedContact;

use Webmozart\Assert\Assert;

class Type
{
    public const OWNER = 'owner';
    public const ADMIN = 'admin';
    public const TECH = 'tech';
    public const BILLING = 'billing';

    private string $name;

    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::OWNER,
            self::ADMIN,
            self::TECH,
            self::BILLING,
        ]);

        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}