<?php

namespace Macellan\Turatel\Tests\TestModels;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public function routeNotificationForSms(): string
    {
        return '905554443322';
    }
}
