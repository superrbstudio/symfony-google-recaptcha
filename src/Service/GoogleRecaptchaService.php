<?php

namespace Superrb\GoogleRecaptchaBundle\Service;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

/**
 * Class GoogleRecaptchaService
 * @package Superrb\GoogleRecaptchaBundle\Service
 */
class GoogleRecaptchaService
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * GoogleRecaptchaService constructor.
     * @param LoggerInterface $logger
     * @param string $secretKey The Recaptcha Site Secret Key
     * @param string $apiUrl The URL for the Google Recaptcha API - https://www.google.com/recaptcha/api/siteverify
     */
    public function __construct(LoggerInterface $logger, string $secretKey, string $apiUrl = 'https://www.google.com/recaptcha/api/siteverify')
    {
        $this->setLogger($logger);
        $this->setSecretKey($secretKey);
        $this->setApiUrl($apiUrl);
    }

    /**
     * Validate the token
     *
     * @param string $token
     * @return bool
     */
    public function validateToken(string $token)
    {
        $data = [
            'secret'   => $this->getSecretKey(),
            'response' => $token,
        ];

        $client   = new Client();
        $response = $client->post($this->getApiUrl(), [
            'query' => $data,
        ]);

        try {
            if (200 !== $response->getStatusCode()) {
                $this->logger->error('Error while validating recaptcha', [
                    'response' => json_decode((string) $response->getBody()),
                ]);

                return false;
            }

            $data = json_decode((string) $response->getBody());

            if (true === $data->success) {
                $this->logger->info('Success while validating recaptcha', [
                    'response' => json_decode((string) $response->getBody()),
                ]);
                
                return true;
            }

            $this->logger->error('Recaptcha challenge failed', [
                'reasons' => $data->{'error-codes'},
            ]);

            return false;
        } catch (\Throwable $th) {
            $this->logger->error('Fatal Error while validating recaptcha', [
                'message'   => $th->getMessage(),
                'exception' => $th,
            ]);

            return false;
        }
        return true;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @return string
     */
    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    /**
     * @param string $secretKey
     */
    public function setSecretKey(string $secretKey): void
    {
        $this->secretKey = $secretKey;
    }

    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * @param string $apiUrl
     */
    public function setApiUrl(string $apiUrl): void
    {
        $this->apiUrl = $apiUrl;
    }
}
