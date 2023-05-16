<?php

namespace Macellan\Turatel\Notifications\Messages;

class TuratelSmsMessage
{
    public function __construct(private string $body, private array $numbers = [], private bool $isOtp = false)
    {
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getNumbers(): array
    {
        return $this->numbers;
    }

    public function setNumbers(array $numbers): TuratelSmsMessage
    {
        $this->numbers = $numbers;
        return $this;
    }

    public function isOtp(): bool
    {
        return $this->isOtp;
    }
}
