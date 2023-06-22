<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class ErrorHandler implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function handle(\DomainException $e): void
    {
        $this->logger->warning($e->getMessage(), ['exception' => $e]);
    }
}
