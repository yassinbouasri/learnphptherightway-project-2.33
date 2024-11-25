<?php

namespace App\Controllers;

use App\Attributes\Route;

class CurlController
{
    #[Route('/curl')]
    public function curl()
    {
        $handle = curl_init();

        $apiKey = $_ENV['EMAILABLE_API_KEY'];


        $email = 'yassinbouasr@gmail.com';

        $params = [
            'email' => $email,
            'api_key' => $apiKey,
        ];
        print_r(http_build_query($params));
        $url = 'https://api.emailable.com/v1/verify?email=' . http_build_query($params);

        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($handle);

        if ($output !== FALSE) {
            $data = json_decode($output, true);
            echo '<pre>';
            print_r($data);
        }
    }

}