<?php

class CreditCard
{
    private $number;
    private $name;
    private $surname;
    private $expiration;

    public function __construct($number, $name, $surname, $expiration)
    {
        $this->number = $number;
        $this->name = $name;
        $this->surname = $surname;
        $this->expiration = $expiration;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function getExpiration()
    {
        return $this->expiration;
    }
}