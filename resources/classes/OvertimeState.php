<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

class OvertimeState extends State {
    public function setBookToLost(): void
    {
        $this->context->transitionTo(new LostState());
    }

    public function returnBook(): void
    {
        $this->context->transitionTo(new OpenState());
    }

    public function validTransactions() : array
    {
        return [LostState::class, OpenState::class];
    }
}
