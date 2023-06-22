<?php

declare(strict_types=1);

namespace App\Model\Domain\Command\Contact\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    public string $id = '';

    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    public string $ownerId = '';

    /**
     * @Assert\NotBlank
     * @Assert\Regex("/^.* .+$/")
     */
    public string $name = '';

    public ?string $organization = null;

    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    public string $email = '';

    /**
     * @Assert\NotBlank
     * @Assert\Regex("/^\+[0-9]{1,4}\.[0-9]{4,12}$/")
     */
    public string $phone = '';

    /**
     * @Assert\NotBlank
     */
    public string $address = '';

    /**
     * @Assert\NotBlank
     */
    public string $city = '';

    /**
     * @Assert\NotBlank
     */
    public string $state = '';

    /**
     * @Assert\NotBlank
     */
    public string $zip = '';

    /**
     * @Assert\NotBlank
     */
    public string $country = '';
}