<?php

namespace App\Controllers;

use App\Attributes\Route;
use App\Contracts\EmailValidationInterface;
use App\Services\Emailable\EmailValidationService;

class CurlController
{
    public function __construct(private EmailValidationInterface $emailValidationInterface)
    {
    }

    #[Route('/curl')]
    public function curl()
    {
        $email = 'yassinbouasri@gmail.com';
        $response = $this->emailValidationInterface->verify($email);


        echo '<pre>';
        print_r($response->score);
        echo '</pre>';
    }

}