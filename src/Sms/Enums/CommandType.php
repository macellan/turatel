<?php

namespace Macellan\Turatel\Sms\Enums;

enum CommandType: int
{
    case SEND_SMS_TO_MANY = 0;

    case CANCEL_JOB = 4;

    case CHECK_DATE = 5;

    case GET_CREDIT = 6;
}
