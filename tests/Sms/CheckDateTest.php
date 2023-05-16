<?php

namespace Macellan\Turatel\Tests\Sms;

use Illuminate\Support\Facades\Http;
use Macellan\Turatel\Sms\Exceptions\TuratelClientException;
use Macellan\Turatel\Sms\Requests\CheckDate;
use Macellan\Turatel\Tests\TestCase;
use Mockery;

class CheckDateTest extends TestCase
{
    public function test_check_date(): void
    {
        Http::fake([
            $this->mockTuratelServiceUrl => Http::response('OK'),
        ]);

        $this->assertSame('OK', call_user_func(new CheckDate(config('services.sms.turatel.sms_service'))));
    }

    public function test_check_date_error(): void
    {
        $this->expectException(TuratelClientException::class);

        Http::fake([
            $this->mockTuratelServiceUrl => Http::response('9876'),
        ]);

        $mockCheckDate = Mockery::mock(CheckDate::class, [config('services.sms.turatel.sms_service')])->makePartial();

        $mockCheckDate
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getErrorCodes')
            ->andReturns(['9876' => 'Error']);

        call_user_func($mockCheckDate, 1);
    }
}