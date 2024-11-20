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
        $sql = "INSERT INTO transactions (date,checkNumber,description,amount) VALUES (:date, :checkNumber, :description, :amount)";
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':date', $date->format('Y-m-d H:i:s'));
        $stmt->bindValue(':checkNumber', $checkNumber);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':amount', $amount);
        try {
            return $stmt->execute();
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
        }
    }

    public function select():array
    {
        $sql = "SELECT * FROM transactions";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}