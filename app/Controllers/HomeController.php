<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Attributes\Route;
use App\Container;
use App\Services\InvoiceService;
use App\Services\SalesTaxService;
use App\Transaction;
use App\View;
use DateTime;
use Twig\Environment as Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HomeController
{
    private Transaction $transaction;
    public function __construct(private InvoiceService $invoiceService, private Twig $twig)
    {
        $this->transaction = new Transaction();
    }

    #[Route('/')]
    public function index(): string
    {
        $this->invoiceService->process([], 25);
        return $this->twig->render('index.twig');
    }

    #[Route('/uploadCSV')]
    public function uploadCSV(): string
    {
        return $this->twig->render('uploadCSV.twig');
    }
    #[Route('/storeCSV', 'post')]
    public function storeCSV(): void
    {
        $transactions = $this->getTransactions($this->getCSVcontent("csv"));
        var_dump($transactions);
        foreach ($transactions as $transaction) {
            $date = DateTime::createFromFormat( "m/d/Y",$transaction["date"]);
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
            var_dump($_FILES);
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

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[Route('/showTransactions')]
    public function showTransactions(): string
    {
        $transactions = $this->transaction->select();
        $totals = $this->totals($transactions);
        return  $this->twig->render('transactions.twig', ['transactions' => $transactions, 'totals' => $totals]);
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
