<?php

namespace Macellan\Turatel\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Macellan\Turatel\Notifications\Messages\TuratelSmsMessage;
use Macellan\Turatel\Sms\Client;

class TuratelSmsChannel
{
    /**
     * If true, will run.
     */
    private bool $enable;

    /**
     * Debug flag. If true, messages send/result wil be stored in Laravel log.
     */
    private bool $debug;

    /**
     * Sandbox mode flag. If true, endpoint API will not be invoked, useful for dev purposes.
     */
    private bool $sandboxMode;

    public function __construct(
        private readonly array $config
    ) {
        $this->enable = Arr::get($this->config, 'enable', false);
        $this->debug = Arr::get($this->config, 'debug', false);
        $this->sandboxMode = Arr::get($this->config, 'sandbox_mode', true);
    }

    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (! $this->enable) {
            $this->log('Turatel is disabled');

            return;
        }

        /** @var TuratelSmsMessage $message */
        $message = $notification->toTuratelSms($notifiable);
        $message->setNumbers(Arr::wrap($notifiable->routeNotificationFor('turatel_sms')));

        $client = $this->getClient();

        $this->log(sprintf('Turatel sending sms - %s', print_r($message, true)));

        if ($this->sandboxMode) {
            return;
        }

        try {
            $responses = $client->sendSms($message->getBody(), $message->getNumbers(), $message->isOtp());

            $this->log('Turatel sms send response - '.print_r($responses, true));
        } catch (\Throwable $e) {
            $this->log(sprintf(
                'Turatel message could not be sent. Error: %s',
                $e->getMessage()
            ));

            throw $e;
        }
    }

    protected function getClient(): Client
    {
        return new Client($this->config);
    }

    private function log(string $message, string $level = 'info', array $context = [])
    {
        if ($this->debug) {
            Log::log($level, $message, $context);
        }
    }
}
