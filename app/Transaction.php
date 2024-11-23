<?php

namespace App;

use DateTime;
use PDO;

class Transaction extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function insert(DateTime $date, string $checkNumber, string $description, float $amount): bool
    {
        try {
            return $this->db->createQueryBuilder()
            ->insert('transactions')
            ->values([
                'date' => $date->format('Y-m-d H:i:s'),
                'checkNumber' => $checkNumber,
                'description' => $description,
                'amount' => $amount
            ]);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
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