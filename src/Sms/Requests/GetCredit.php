<?php

namespace Macellan\Turatel\Sms\Requests;

use Macellan\Turatel\Sms\Enums\CommandType;
use Macellan\Turatel\Sms\Exceptions\TuratelClientException;

class GetCredit extends BaseRequest
{
    /**
     * @throws TuratelClientException
     */
    public function __invoke(): string
    {
        $response = $this->request([], CommandType::GET_CREDIT, 'MainReportRoot');

        return $response->body();
    }

    protected function getErrorCodes(): array
    {
        return [
            '01' => 'Kullanıcı adı ya da şifre hatalı',
        ];
    }
}
