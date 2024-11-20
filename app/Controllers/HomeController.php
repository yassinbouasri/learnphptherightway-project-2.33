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
        $transactions = $this->getTransactions($this->getCSVcontent("csv"));
        echo "<pre>";
        $x = [];
        foreach ($transactions as $transaction) {
           $x = $transaction;
        }

        var_dump( $x);
    }

    private function getCSVcontent(string $fileName): array
    {
        if ($_FILES[$fileName]['error'] !== 0) {
            echo "Error while trying to open " . $fileName . "<br>";
            return [];
        }
        $array = file($_FILES[$fileName]['tmp_name'], FILE_IGNORE_NEW_LINES);
        unset($array[0]);
        return $array;
    }

    /**
     * @return array
     */
    private function getTransactions(array $csv): array
    {
        $transaction = [];
        foreach ($csv as $row) {
            $amount = explode(",", $row)[3];
            $transaction[] = [
                "date" => explode(",", $row)[0] ?? null,
                "check" => explode(",", $row)[1] ?? null,
                "description" => explode(",", $row)[2] ?? null,
                "amount" =>  (float) str_replace(['$','"'],"", $amount) ?? null
            ];
        }
        return $transaction;
    }
}
