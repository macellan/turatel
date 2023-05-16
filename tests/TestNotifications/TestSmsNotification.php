<?php

namespace Macellan\Turatel\Tests\TestNotifications;

use Illuminate\Notifications\Notification;
use Macellan\Turatel\Notifications\Messages\TuratelSmsMessage;

class TestSmsNotification extends Notification
{
    public function via(): array
    {
        return ['turatel_sms'];
    }

    public function toTuratelSms(): TuratelSmsMessage
    {
        return new TuratelSmsMessage('Test message');
    }
}
