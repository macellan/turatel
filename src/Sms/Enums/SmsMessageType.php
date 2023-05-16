<?php

namespace Macellan\Turatel\Sms\Enums;

enum SmsMessageType: int
{
    case STANDARD = 1;

    case STANDARD_TURKISH = 2;

    case BINARY = 3;

    case FLASH = 4;

    case WAP_PUSH = 5;

    case FLASH_TURKISH = 7;
}
