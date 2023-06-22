<?php

declare(strict_types=1);

namespace App\Model\Domain\Entity\Contact;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Embeddable
 */
class Phone
{
    /**
     * @ORM\Column(type="integer")
     */
    private int $countryCode;
    /**
     * @ORM\Column(type="integer")
     */
    private int $number;

    public function __construct(int $countryCode, int $number)
    {
        Assert::notEmpty($countryCode);
        Assert::notEmpty($number);

        $this->countryCode = $countryCode;
        $this->number = $number;
    }

    public function getCountryCode(): int
    {
        return $this->countryCode;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getFull(): string
    {
        return '+' . $this->countryCode . '.' . $this->number;
    }
}
