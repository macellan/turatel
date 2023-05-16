<?php

namespace Macellan\Turatel\Sms\Requests;

use Macellan\Turatel\Sms\DTO\SendSmsRequestDTO;
use Macellan\Turatel\Sms\Enums\CommandType;
use Macellan\Turatel\Sms\Exceptions\TuratelClientException;

class SendSms extends BaseRequest
{
    public const MAX_NUMBER = 50000;

    /**
     * @throws TuratelClientException
     */
    public function send(SendSmsRequestDTO $sendSmsRequestDTO): string
    {
        $response = $this->request(
            $sendSmsRequestDTO->getRequestData(),
            CommandType::SEND_SMS_TO_MANY,
            'MainmsgBody'
        );

        $responseBody = $response->body();
        if (! str_contains($responseBody, 'ID:')) {
            throw new TuratelClientException(sprintf('Failed to get sms ID number. Response: %s', $responseBody));
        }

        return filter_var($responseBody, FILTER_SANITIZE_NUMBER_INT);
    }

    protected function getErrorCodes(): array
    {
        return [
            '01' => 'Kullanıcı adı ya da şifre hatalı',
            '02' => 'Kredisi yeterli değil',
            '03' => 'Geçersiz içerik',
            '04' => 'Bilinmeyen SMS tipi',
            '05' => 'Hatalı gönderen ismi',
            '06' => 'Mesaj metni ya da Alıcı bilgisi girilmemiş',
            '07' => 'İçerik uzun fakat Concat özelliği ayarlanmadığından mesaj birleştirilemiyor',
            '08' => 'Kullanıcının mesaj göndereceği gateway tanımlı değil ya da şu anda çalışmıyor',
            '09' => 'Yanlış tarih formatı.Tarih ddMMyyyyhhmm formatında olmalıdır',
        ];
    }
}
