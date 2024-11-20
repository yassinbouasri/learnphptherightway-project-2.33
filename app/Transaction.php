<?php

namespace App;

use DateTime;

class Transaction extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function insert(DateTime $date, int $check, string $description, float $amount): bool
    {
        $sql = "INSERT INTO transactions (date, check_#, description, amount) VALUES (:date, :check_#, :description, :amount)";
        $stmt = $this->db->prepare($sql);

        $format = $date->format('Y-m-d H:i:s');
        $stmt->bindParam(':date', $format);
        $stmt->bindParam(':check_#', $check);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':amount', $amount);
        try {
            return $stmt->execute();
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
        }
    }


}