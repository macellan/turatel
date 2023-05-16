<?php

namespace Macellan\Turatel\Tests;

use Illuminate\Contracts\Config\Repository;
use Macellan\Turatel\TuratelServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected string $mockTuratelServiceUrl = 'https://turatel-service.test';

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app)
    {
        return [
            TuratelServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        tap($app->make('config'), function (Repository $config) {
            $config->set('services.sms.turatel.sms_service', [
                'enable' => true,
                'debug' => true,
                'sandbox_mode' => false,
                'base_url' => $this->mockTuratelServiceUrl,
                'platform_id' => 0,
                'channel_code' => 0,
                'user_name' => '',
                'password' => '',
                'originator' => '',
            ]);
        });
    }
}
