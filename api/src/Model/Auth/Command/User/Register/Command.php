<?php

declare(strict_types=1);

namespace App\Model\Auth\Command\User\Register;

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
     * @Assert\Regex("/^.* .+$/")
     */
    public string $name = '';

    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    public string $email = '';

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=6)
     */
    public string $password = '';
}