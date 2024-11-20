<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Transaction;
use App\View;
use DateTime;

class HomeController
{
    private Transaction $transaction;
    public function __construct()
    {
        $this->transaction = new Transaction();
    }

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
        foreach ($transactions as $transaction) {
            $date = DateTime::createFromFormat( "Y-m-d","2021-06-21");
            $check = $transaction["check"] ?? null;
            $description = $transaction["description"] ?? null;
            $amount = $transaction["amount"] ?? null;
            $this->transaction->insert($date,$check ,$description, $amount);
        }
    }

    private function getCSVcontent(string $fileName): array
    {
        if ($_FILES[$fileName]['error'] !== 0) {
            echo "Error while trying to open " . $fileName . "<br>";
            return [];
        }
        $file = fopen($_FILES[$fileName]['tmp_name'], "r");
        fgetcsv($file);

        $transactions = [];
        while ($transaction = fgetcsv($file)) {
            $transactions[] = $transaction;
        }
        return $transactions;
    }

    /**
     * @return array
     */
    private function getTransactions(array $csv): array
    {
        $transactionsKeyVal = [];

        foreach ($csv as $row) {
            $transactionsKeyVal[] = [
                "date" => $row[0],
                "check" => $row[1],
                "description" => $row[2],
                "amount" => (float)str_replace(['$',','],'', $row[3]),
            ];
        }


        return $transactionsKeyVal;
    }

    public function showTransactions(): View
    {
        $transactions = $this->transaction->select();
        echo "<pre>";
        $totals = $this->totals($transactions);
        return View::make('transactions', ['transactions' => $transactions, 'totals' => $totals]);
    }

    private function totals(array $transactions): array
    {
        $totals = ['netTotal' => 0, 'totalIncome' => 0, 'totalExpense' => 0];
        foreach ($transactions as $transaction) {
            $totals['netTotal'] += $transaction['amount'];
            if ($transaction['amount'] <= 0){
                $totals['totalExpense'] += $transaction['amount'];
            } else
            {
                $totals['totalIncome'] += $transaction['amount'];
            }
        }
        return $totals;
    }
}
