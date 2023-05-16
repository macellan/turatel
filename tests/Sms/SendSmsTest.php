<?php

namespace Macellan\Turatel\Tests\Sms;

use Illuminate\Support\Facades\Http;
use Macellan\Turatel\Sms\DTO\SendSmsRequestDTO;
use Macellan\Turatel\Sms\Exceptions\TuratelClientException;
use Macellan\Turatel\Sms\Requests\SendSms;
use Macellan\Turatel\Tests\TestCase;
use Mockery;

class SendSmsTest extends TestCase
{
    public function test_send_sms(): void
    {
        Http::fake([
            $this->mockTuratelServiceUrl => Http::response('ID:123'),
        ]);

        $sendSms = new SendSms(config('services.sms.turatel.sms_service'));
        $sendSmsRequestDTO = new SendSmsRequestDTO('test', ['905554443322'], 'Test');

        $this->assertSame('123', $sendSms->send($sendSmsRequestDTO));
    }

    public function test_can_not_contain_id(): void
    {
        $this->expectException(TuratelClientException::class);

        Http::fake([
            $this->mockTuratelServiceUrl => Http::response('123'),
        ]);

        $sendSms = new SendSms(config('services.sms.turatel.sms_service'));
        $sendSmsRequestDTO = new SendSmsRequestDTO('test', ['905554443322'], 'Test');

        $sendSms->send($sendSmsRequestDTO);
    }

    public function test_send_sms_error(): void
    {
        $this->expectException(TuratelClientException::class);

        Http::fake([
            $this->mockTuratelServiceUrl => Http::response('9876'),
        ]);

        $mockSendSms = Mockery::mock(SendSms::class, [config('services.sms.turatel.sms_service')])->makePartial();

        $mockSendSms
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getErrorCodes')
            ->andReturns(['9876' => 'Error']);

        $sendSmsRequestDTO = new SendSmsRequestDTO('test', ['905554443322'], 'Test');

        call_user_func($mockSendSms->send($sendSmsRequestDTO));
    }
}