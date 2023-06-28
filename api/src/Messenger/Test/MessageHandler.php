<?php

declare(strict_types=1);

namespace App\Messenger\Test;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class MessageHandler implements MessageHandlerInterface
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function __invoke(Message $message): void
    {
        $name = $message->getName();
        $currentTime = (new \DateTimeImmutable())->getTimestamp();
        $this->logger->info(
            '[' . $name . '] Handling started:'
            . ' Message init time: ' . (string)$message->getTime()
            . ' Current time: ' . (string)$currentTime
            . ' Delivery time: ' . (string)($currentTime - $message->getTime())
        );

        sleep(20);
        $currentTime = (new \DateTimeImmutable())->getTimestamp();
        $this->logger->info(
            '[' . $name . '] Handling finished:'
            . ' Processing time: ' . (string)($currentTime - $message->getTime())
        );
    }
}