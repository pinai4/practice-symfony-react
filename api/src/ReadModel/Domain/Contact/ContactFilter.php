<?php

declare(strict_types=1);

namespace App\ReadModel\Domain\Contact;

class ContactFilter
{
    public ?string $name = null;
    public ?string $organization = null;
    public ?string $email = null;
    public ?string $phone = null;
    public ?string $address = null;
    public ?string $city = null;
    public ?string $state = null;
    public ?string $zip = null;
    public ?string $country = null;
}