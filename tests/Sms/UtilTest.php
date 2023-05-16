<?php

namespace Macellan\Turatel\Tests\Sms;

use Macellan\Turatel\Sms\Enums\SmsMessageType;
use Macellan\Turatel\Sms\Util;
use Macellan\Turatel\Tests\TestCase;

class UtilTest extends TestCase
{
    public function test_concatable_message(): void
    {
        $message = str_repeat('a', 161);

        $this->assertSame(1, Util::getConcatValue($message, SmsMessageType::STANDARD));
        $this->assertSame(1, Util::getConcatValue($message, SmsMessageType::FLASH));

        $message = str_repeat('a', 71);

        $this->assertSame(1, Util::getConcatValue($message, SmsMessageType::STANDARD_TURKISH));
        $this->assertSame(1, Util::getConcatValue($message, SmsMessageType::FLASH_TURKISH));
    }

    public function test_not_concatable_message(): void
    {
        $message = str_repeat('a', 140);

        $this->assertSame(0, Util::getConcatValue($message, SmsMessageType::STANDARD));
        $this->assertSame(0, Util::getConcatValue($message, SmsMessageType::FLASH));

        $message = str_repeat('a', 50);

        $this->assertSame(0, Util::getConcatValue($message, SmsMessageType::STANDARD_TURKISH));
        $this->assertSame(0, Util::getConcatValue($message, SmsMessageType::FLASH_TURKISH));
    }

    public function test_concat_value_wap_push(): void
    {
        $message = str_repeat('a', 10);

        $this->assertNull(Util::getConcatValue($message, SmsMessageType::WAP_PUSH));
    }
}
