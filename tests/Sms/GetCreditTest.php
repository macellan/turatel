<?php

namespace Macellan\Turatel\Tests\Sms;

use Illuminate\Support\Facades\Http;
use Macellan\Turatel\Sms\Exceptions\TuratelClientException;
use Macellan\Turatel\Sms\Requests\GetCredit;
use Macellan\Turatel\Tests\TestCase;
use Mockery;

class GetCreditTest extends TestCase
{
    public function test_get_credit(): void
    {
        Http::fake([
            $this->mockTuratelServiceUrl => Http::response('OK'),
        ]);

        $this->assertSame('OK', call_user_func(new GetCredit(config('services.sms.turatel.sms_service'))));
    }

    public function test_get_credit_error(): void
    {
        $this->expectException(TuratelClientException::class);

        Http::fake([
            $this->mockTuratelServiceUrl => Http::response('9876'),
        ]);

        $mockGetCredit = Mockery::mock(GetCredit::class, [config('services.sms.turatel.sms_service')])->makePartial();

        $mockGetCredit
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getErrorCodes')
            ->andReturns(['9876' => 'Error']);

        call_user_func($mockGetCredit);
    }
}