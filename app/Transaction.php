<?php

namespace App;

class Transaction extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function insert()
    {
        $sql = "INSERT INTO transaction (date, check_#, description, amount) VALUES (:date, :check_#, :description, :amount)";
    }


}