<?php

namespace Macellan\Turatel\Tests;

use Illuminate\Support\Facades\Http;
use Macellan\Turatel\Notifications\Channels\TuratelSmsChannel;
use Macellan\Turatel\Sms\Client;
use Macellan\Turatel\Sms\Exceptions\TuratelClientException;
use Macellan\Turatel\Tests\TestModels\User;
use Macellan\Turatel\Tests\TestNotifications\TestSmsNotification;
use Mockery;

class TuratelSmsChannelTest extends TestCase
{
    private Client $client;

    private array $config;

    public function setUp(): void
    {
        parent::setUp();

        $this->config = config('services.sms.turatel.sms_service');

        $this->client = Mockery::mock(Client::class, [$this->config])->makePartial();
    }

    public function test_send_notification(): void
    {
        $this->config['enable'] = true;
        $this->config['debug'] = true;
        $this->config['sandbox_mode'] = false;

        Http::fake([
            $this->mockTuratelServiceUrl => Http::response('ID:123'),
        ]);

        $channel = Mockery::mock(TuratelSmsChannel::class, [$this->config])->makePartial();
        $channel
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getClient')
            ->andReturn($this->client);

        $channel->send(new User(), new TestSmsNotification());

        $this->client->shouldHaveReceived('sendSms');
    }

    public function test_disable_not_send_notification(): void
    {
        $this->config['enable'] = false;
        $this->config['debug'] = true;
        $this->config['sandbox_mode'] = false;

        $channel = Mockery::mock(TuratelSmsChannel::class, [$this->config])->makePartial();
        $channel->shouldAllowMockingProtectedMethods();

        $channel->send(new User(), new TestSmsNotification());

        $channel->shouldNotHaveReceived('getClient');
        $this->client->shouldNotHaveReceived('sendSms');
    }

    public function test_sandbox_mode_not_send_notification(): void
    {
        $this->config['enable'] = true;
        $this->config['debug'] = false;
        $this->config['sandbox_mode'] = true;

        $channel = Mockery::mock(TuratelSmsChannel::class, [$this->config])->makePartial();

        $channel->send(new User(), new TestSmsNotification());

        $this->client->shouldNotHaveReceived('sendSms');
    }

    public function test_throw_exception_not_send_notification(): void
    {
        $this->config['enable'] = true;
        $this->config['debug'] = true;
        $this->config['sandbox_mode'] = false;

        $this->expectException(TuratelClientException::class);

        $channel = Mockery::mock(TuratelSmsChannel::class, [$this->config])->makePartial();
        $channel
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getClient')
            ->andReturn($this->client);

        $this->client->shouldReceive('sendSms')
            ->andThrows(TuratelClientException::class);

        $channel->send(new User(), new TestSmsNotification());
    }
}
