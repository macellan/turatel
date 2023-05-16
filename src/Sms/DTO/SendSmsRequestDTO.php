<?php

namespace Macellan\Turatel\Sms\DTO;

use Macellan\Turatel\Sms\Enums\SmsMessageType;
use DateTimeInterface;

class SendSmsRequestDTO
{
    private SmsMessageType $type = SmsMessageType::STANDARD;

    private ?DateTimeInterface $startDate = null;

    private ?DateTimeInterface $endDate = null;

    private ?int $concat = null;

    private bool $isOtp = false;

    public function __construct(
        private string $message,
        private array $numbers,
        private string $originator
    ) {
    }

    public function setType(SmsMessageType $type): SendSmsRequestDTO
    {
        $this->type = $type;
        return $this;
    }

    public function setStartDate(?DateTimeInterface $startDate): SendSmsRequestDTO
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function setEndDate(?DateTimeInterface $endDate): SendSmsRequestDTO
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function setConcat(?int $concat): SendSmsRequestDTO
    {
        $this->concat = $concat;
        return $this;
    }

    public function isOtp(): bool
    {
        return $this->isOtp;
    }

    public function setIsOtp(bool $isOtp): SendSmsRequestDTO
    {
        $this->isOtp = $isOtp;
        return $this;
    }

    public function getRequestData(): array
    {
        $data = [
            'Mesgbody' => $this->message,
            'Numbers' => implode(',', $this->numbers),
            'Type' => $this->type->value,
            'Originator' => $this->originator,
            'Option' => (int) $this->isOtp,
        ];

        if ($this->startDate) {
            $data['SDate'] = $this->startDate->format('dMYHi');
        }

        if ($this->endDate) {
            $data['EDate'] = $this->endDate->format('dMYHi');
        }

        if (! is_null($this->concat)) {
            $data['Concat'] = $this->concat;
        }

        return $data;
    }
}