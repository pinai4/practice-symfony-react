<?php

declare(strict_types=1);

namespace App\Model\Auth\Service;

use App\Model\Auth\Entity\User\Email;

interface RegisterSenderInterface
{
    public function send(Email $emailAddress): void;
}
