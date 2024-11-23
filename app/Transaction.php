<?php

namespace App;

use DateTime;
use Doctrine\DBAL\Exception;
use PDO;

class Transaction extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function insert(DateTime $date, string $checkNumber, string $description, float $amount): \Doctrine\DBAL\Result
    {
        try {
            return $this->db->createQueryBuilder()
                ->insert('transactions')
                ->values([
                    'date' => ':date',
                    'checkNumber' => ':checkNumber',
                    'description' => ':description',
                    'amount' => ':amount'
                ])
                ->setParameter(':date', $date)
                ->setParameter(':checkNumber', $checkNumber)
                ->setParameter(':description', $description)
                ->setParameter(':amount', $amount)
                ->executeQuery();

        }  catch (Exception $e) {
            return false;
        }
    }

    public function select():array
    {
        return $this->db->createQueryBuilder()
            ->select('*')
            ->from('transactions')
            ->fetchAllAssociative()
        ;
    }


}