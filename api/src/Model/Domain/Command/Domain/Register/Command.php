<?php

declare(strict_types=1);

namespace App\Model\Domain\Command\Domain\Register;

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
     * @Assert\Hostname
     */
    public string $name = '';
    /**
     * @Assert\NotBlank
     * @Assert\Range(min="1",max="10")
     */
    public int $period = 0;
    /**
     * @Assert\NotBlank
     * @Assert\Uuid
     */
    public string $ownerContactId = '';
}