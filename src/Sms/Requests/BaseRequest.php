<?php

namespace Macellan\Turatel\Sms\Requests;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Macellan\Turatel\Sms\Enums\CommandType;
use Macellan\Turatel\Sms\Exceptions\HttpClientException;
use Macellan\Turatel\Sms\Exceptions\TuratelClientException;
use Spatie\ArrayToXml\ArrayToXml;
use Throwable;

abstract class BaseRequest
{
    private const GENERAL_ERRORS = [
        '00' => 'Sistem Hatası',
        '20' => 'Tanımsız Hata (XML formatını kontrol ediniz veya TURATEL’den destek alınız)',
        '21' => 'Hatalı XML Formatı (\n - carriage return – newline vb içeriyor olabilir)',
        '22' => 'Kullanıcı Aktif Değil',
        '23' => 'Kullanıcı Zaman Aşımında',
    ];

    public function __construct(protected array $config = [])
    {
    }

    abstract protected function getErrorCodes(): array;

    private function getCredentials(): array
    {
        return [
            'PlatformID' => $this->config['platform_id'] ?? 1,
            'ChannelCode' => $this->config['channel_code'] ?? 0,
            'UserName' => $this->config['user_name'] ?? '',
            'PassWord' => $this->config['password'] ?? '',
        ];
    }

    /**
     * @throws TuratelClientException
     */
    protected function request(array $data, CommandType $command, string $rootElement): Response
    {
        $data = array_merge($this->getCredentials(), $data, ['Command' => $command->value]);

        try {
            $response = Http::timeout(30)
                ->withBody(
                    ArrayToXml::convert($data, $rootElement, true, 'UTF-8'),
                    'application/xml'
                )
                ->post($this->config['base_url'] ?? 'https://processor.smsorigin.com/xml/process.aspx')
                ->throw();
        } catch (Throwable $e) {
            throw new HttpClientException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }

        $this->checkErrors($response);

        return $response;
    }

    /**
     * @throws TuratelClientException
     */
    private function checkErrors(Response $response): void
    {
        $responseBody = $response->body();

        if (in_array($responseBody, array_keys(self::GENERAL_ERRORS))) {
            throw new TuratelClientException(sprintf(
                'Turatel error code: %s. Description: %s',
                $responseBody,
                self::GENERAL_ERRORS[$responseBody]
            ));
        }

        $apiErrorCodes = $this->getErrorCodes();

        if ($apiErrorCodes && in_array($responseBody, array_keys($apiErrorCodes))) {
            throw new TuratelClientException(sprintf(
                'Turatel error code: %s. Description: %s',
                $responseBody,
                $apiErrorCodes[$responseBody] ?? ''
            ));
        }
    }
}
