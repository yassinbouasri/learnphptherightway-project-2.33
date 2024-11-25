<?php

declare(strict_types=1);

namespace App\Services\Emailable;



use GuzzleHttp\Client;

class EmailValidationService
{
    const BASEURL = 'https://api.emailable.com/v1/';

    public function __construct(private string $apiKey)
    {
    }

    public function verify(string $email): array
    {
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
        ];

        $response = $client->get('verify', ['query' => $params]);

        $url = self::BASEURL . 'verify?' . http_build_query($params);
        echo $url;

        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($handle);

        if ($output !== FALSE) {
            $data = json_decode($output, true);
            echo '<pre>';
            print_r($data);
        }
        return [];
    }
}