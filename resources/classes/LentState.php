<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

class LentState extends State {
    public function setBookToLost(): void
    {
        $this->context->transitionTo(new LostState());
    }

    public function setBookToOvertime(): void
    {
        $this->context->transitionTo(new OvertimeState());
    }

    public function returnBook(): void
    {
        $this->context->transitionTo(new OpenState());
    }

    public function validTransactions() : array
    {
        return [OpenState::class, OvertimeState::class, LostState::class];
    }
}