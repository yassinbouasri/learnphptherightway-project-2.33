<?php

namespace App\Controllers;

use App\Attributes\Route;
use App\Services\Emailable\EmailValidationService;

class CurlController
{
    public function __construct(private EmailValidationService $emailValidationService)
    {
    }

    #[Route('/curl')]
    public function curl()
    {
        $email = 'yassinbouasri@gmail.com';
        $response = $this->emailValidationService->verify($email);

        echo '<pre>';
        print_r($response);
        echo '</pre>';
    }

}