<?php

namespace Macellan\Turatel\Tests\Sms;

use Illuminate\Support\Facades\Http;
use Macellan\Turatel\Sms\Exceptions\TuratelClientException;
use Macellan\Turatel\Sms\Requests\CancelJob;
use Macellan\Turatel\Tests\TestCase;
use Mockery;

class CancelJobTest extends TestCase
{
    public function test_cancel_job(): void
    {
        Http::fake([
            $this->mockTuratelServiceUrl => Http::response('OK'),
        ]);

        $this->assertSame('OK', call_user_func(new CancelJob(config('services.sms.turatel.sms_service')), 1));
    }

    public function test_cancel_job_error(): void
    {
        $this->expectException(TuratelClientException::class);

        Http::fake([
            $this->mockTuratelServiceUrl => Http::response('9876'),
        ]);

        $mockCancelJob = Mockery::mock(CancelJob::class, [config('services.sms.turatel.sms_service')])->makePartial();

        $mockCancelJob
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getErrorCodes')
            ->andReturns(['9876' => 'Error']);

        call_user_func($mockCancelJob, 1);
    }
}