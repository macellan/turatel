<?php

namespace Macellan\Turatel\Sms\Requests;

use Macellan\Turatel\Sms\Enums\CommandType;
use Macellan\Turatel\Sms\Exceptions\TuratelClientException;

class CancelJob extends BaseRequest
{
    /**
     * @throws TuratelClientException
     */
    public function __invoke(int $id): string
    {
        $response = $this->request(
            ['MsgID' => $id,],
            CommandType::CANCEL_JOB,
            'CancelJob'
        );

        return $response->body();
    }

    protected function getErrorCodes(): array
    {
        return [
            '01' => 'Kullanıcı adı ya da şifre hatalı',
            '02' => 'Gönderim iptal edilemedi. Gönderim tamamlandı veya devam ediyor',
        ];
    }
}
