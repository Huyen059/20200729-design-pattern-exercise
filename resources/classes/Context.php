<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

class Context {
    /**
     * @var State;
     */
    private $state;

    /**
     * CurrentState constructor.
     * @param State $state
     */
    public function __construct(State $state)
    {
        $this->transitionTo($state);
    }

    /**
     * @return State
     */
    public function getState(): State
    {
        return $this->state;
    }

    public function transitionTo(State $state): void
    {
        $this->state = $state;
        $this->state->setContext($this);
    }

    public function borrow(): void
    {
        $this->state->borrowBook();
    }

    public function buy(): void
    {
        $this->state->buyBook();
    }

    public function reportLost(): void
    {
        $this->state->setBookToLost();
    }

    public function setOvertime(): void
    {
        $this->state->setBookToOvertime();
    }

    public function return(): void
    {
        $this->state->returnBook();
    }
}