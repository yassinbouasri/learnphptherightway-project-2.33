<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\View;

class HomeController
{
    public function index(): View
    {
        return View::make('index');
    }

    public function uploadCSV(): View
    {
        return View::make('uploadCSV');
    }
    public function storeCSV(): void
    {

    }
}
