<?php

declare(strict_types=1);

namespace App\Services\Emailable;



use App\Contracts\EmailValidationInterface;
use App\DTO\EmailValidationResult;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class EmailValidationService implements EmailValidationInterface
{
    const BASEURL = 'https://api.emailable.com/v1/';

    public function __construct(private string $apiKey)
    {
    }

    public function verify(string $email): EmailValidationResult
    {
        $stack = HandlerStack::create();

        $maxRetry = 3;

        $stack->push($this->getRetryMiddleware($maxRetry));

        $client = new Client(
            [
                'base_uri' => self::BASEURL,
                'timeout' => 5
            ]
        );


        $email = 'yassinbouasri@gmail.com';

        $params = [
            'email' => $email,
            'api_key' => $this->apiKey,
            'handler' => $stack,
        ];

        $response = $client->get('verify', ['query' => $params]);

        $body = json_decode($response->getBody()->getContents(), true);

        return new EmailValidationResult($body['score'], $body['state'] === 'deliverable');
    }

    private function getRetryMiddleware(int $maxRetry): callable
    {
       return Middleware::retry(
            function (
                int $retries,
                RequestInterface $request,
                ?ResponseInterface $response = null,
                ?\RuntimeException $e = null
            ) use ($maxRetry) {
                if ($retries >= $maxRetry) {
                    return false;
                }

                if ($response && in_array($response->getStatusCode(), [249, 429, 503])) {
                    return true;
                }

                if ($e instanceof ConnectException) {
                    return true;
                }
                return false;
            }
        );
    }
}