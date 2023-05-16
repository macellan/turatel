<?php

namespace Macellan\Turatel\Sms;

use Macellan\Turatel\Sms\Requests\CancelJob;
use Macellan\Turatel\Sms\Requests\CheckDate;
use Macellan\Turatel\Sms\Requests\GetCredit;
use Macellan\Turatel\Sms\Requests\SendSms;
use Macellan\Turatel\Sms\DTO\SendSmsRequestDTO;
use Macellan\Turatel\Sms\Enums\SmsMessageType;
use DateTimeInterface;

class Client
{
    public function __construct(private readonly array $config = [])
    {
    }

    public function sendSms(
        string $message,
        array $numbers,
        bool $isOtp = false,
        ?DateTimeInterface $startDate = null,
        ?DateTimeInterface $endDate = null,
        ?SmsMessageType $type = null
    ): array {
        $type ??= SmsMessageType::STANDARD;

        $sendSmsApi = new SendSms($this->config);

        $responses = [];
        foreach (array_chunk($numbers, SendSms::MAX_NUMBER) as $chunkedNumbers) {
            $sendSmsRequestDTO = (new SendSmsRequestDTO($message, $chunkedNumbers, $this->config['originator'] ?? ''))
                ->setIsOtp($isOtp)
                ->setType($type)
                ->setStartDate($startDate)
                ->setEndDate($endDate)
                ->setConcat(Util::getConcatValue($message, $type));

            $responses[] = $sendSmsApi->send($sendSmsRequestDTO);
        }

        return $responses;
    }

    public function checkDate(): string
    {
        return call_user_func(new CheckDate($this->config));
    }

    public function getCredit(): string
    {
        return call_user_func(new GetCredit($this->config));
    }

    public function cancelJob(int $id): string
    {
        return call_user_func(new CancelJob($this->config), $id);
    }
}
