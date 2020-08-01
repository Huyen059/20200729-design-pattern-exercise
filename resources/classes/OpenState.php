<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

class OpenState extends State {

    public function borrowBook(): void
    {
        $this->context->transitionTo(new LentState());
    }

    public function buyBook(): void
    {
        $this->context->transitionTo(new SoldState());
    }

    public function validTransactions() : array
    {
        return [LentState::class, SoldState::class];
    }
}
