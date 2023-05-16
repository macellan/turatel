<?php

namespace Macellan\Turatel\Sms\Requests;

use Macellan\Turatel\Sms\Enums\CommandType;
use Macellan\Turatel\Sms\Exceptions\TuratelClientException;

class CheckDate extends BaseRequest
{
    /**
     * @throws TuratelClientException
     */
    public function __invoke(): string
    {
        $response = $this->request([], CommandType::CHECK_DATE, 'CheckDate');

        return $response->body();
    }

    protected function getErrorCodes(): array
    {
        return [
            '01' => 'Kullanıcı adı ya da şifre hatalı',
            '07' => 'Genel Hata',
        ];
    }
}
