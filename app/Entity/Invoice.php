<?php

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;

#[Entity]
#[Table('transactions')]
class Invoice
{
    #[id]
    #[Column, GeneratedValue]
    private int $id;

    #[Column(name: 'date')]
    private DateTime $date;

    #[Column(name: 'checkNumber')]
    private int $checkNumber;

    #[Column(name: 'description')]
    private string $description;

    #[Column(name: 'amount')]
    private float $amount;

    public function getId(): int
    {
        return $this->id;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    public function getCheckNumber(): int
    {
        return $this->checkNumber;
    }

    public function setCheckNumber(int $checkNumber): void
    {
        $this->checkNumber = $checkNumber;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }
}