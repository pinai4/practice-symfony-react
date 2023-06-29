<?php

declare(strict_types=1);

namespace App\Messenger\Test;

class Message
{
    private string $name;

    private int $time;

    public function __construct(string $name)
    {
        $this->time = (new \DateTimeImmutable())->getTimestamp();
        $this->name = $name;
    }

    public function getTime(): int
    {
        return $this->time;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
