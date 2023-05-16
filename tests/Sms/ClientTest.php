<?php

namespace Macellan\Turatel\Tests\Sms;

use DateTime;
use Illuminate\Support\Facades\Http;
use Macellan\Turatel\Sms\Client;
use Macellan\Turatel\Sms\Exceptions\HttpClientException;
use Macellan\Turatel\Sms\Exceptions\TuratelClientException;
use Macellan\Turatel\Tests\TestCase;

class ClientTest extends TestCase
{
    private Client $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = new Client(config('services.sms.turatel.sms_service'));
    }

    public function test_can_throw_general_error(): void
    {
        $this->expectException(TuratelClientException::class);

        Http::fake([
            $this->mockTuratelServiceUrl => Http::response('00'),
        ]);

        call_user_func($this->client->getCredit());
    }

    public function test_can_throw_http_client_exception(): void
    {
        $this->expectException(HttpClientException::class);

        Http::fake([
            $this->mockTuratelServiceUrl => Http::response('OK', 403),
        ]);

        call_user_func($this->client->checkDate());
    }

    public function test_send_sms(): void
    {
        Http::fake([
            $this->mockTuratelServiceUrl => Http::response('ID:123'),
        ]);

        $responses = $this->client->sendSms(
            'Test Message',
            ['905554443322'],
            true,
            new DateTime(),
            new DateTime()
        );

        $this->assertSame('123', $responses[0]);
    }

    public function test_check_date(): void
    {
        Http::fake([
            $this->mockTuratelServiceUrl => Http::response('OK'),
        ]);

        $this->assertSame('OK', $this->client->checkDate());
    }

    public function test_get_credit(): void
    {
        Http::fake([
            $this->mockTuratelServiceUrl => Http::response('OK'),
        ]);

        $this->assertSame('OK', $this->client->getCredit());
    }

    public function test_cancel_job(): void
    {
        Http::fake([
            $this->mockTuratelServiceUrl => Http::response('OK'),
        ]);

        $this->assertSame('OK', $this->client->cancelJob(1));
    }
}