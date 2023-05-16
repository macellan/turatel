<?php

namespace Macellan\Turatel;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Macellan\Turatel\Notifications\Channels\TuratelSmsChannel;

class TuratelServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        Notification::resolved(function (ChannelManager $service) {
            $smsServiceConfig = config('services.sms.turatel.sms_service');

            $service->extend('turatel_sms', function () use ($smsServiceConfig) {
                return new TuratelSmsChannel($smsServiceConfig, false);
            });
        });
    }
}
