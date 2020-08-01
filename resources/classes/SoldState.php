<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

class SoldState extends State
{
    public function validTransactions() : array
    {
        return [];
    }
}