<?php

namespace Macellan\Turatel\Sms;

use Macellan\Turatel\Sms\Enums\SmsMessageType;

class Util
{
    public static function getConcatValue(string $message, SmsMessageType $type): ?int
    {
        $concat = null;
        $perMessageCount = match ($type) {
            SmsMessageType::STANDARD, SmsMessageType::FLASH => 160,
            SmsMessageType::STANDARD_TURKISH, SmsMessageType::FLASH_TURKISH => 70,
            default => null,
        };

        if ($perMessageCount) {
            $messageLength = mb_strlen($message, 'UTF-8');

            $concat = $messageLength > $perMessageCount ? 1 : 0;
        }

        return $concat;
    }
}
