<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

abstract class State {
    /**
     * @var Context
     */
    protected Context $context;

    /**
     * @param Context $context
     */
    public function setContext(Context $context): void
    {
        $this->context = $context;
    }
    public function isVisible():bool
    {
        switch (get_class($this)){
            case 'OpenState':
            case 'LentState':
            case 'OvertimeState':
                return true;
            case 'LostState':
            case 'SoldState':
                return false;
        }
    }
    public function borrowBook(): void
    {
        throw new \RuntimeException('Sorry. Your request can\'t be done.');
    }
    public function buyBook(): void
    {
        throw new \RuntimeException('Sorry. Your request can\'t be done.');
    }
    public function setBookToLost(): void
    {
        throw new \RuntimeException('Sorry. Your request can\'t be done.');
    }
    public function setBookToOvertime(): void
    {
        throw new \RuntimeException('Sorry. Your request can\'t be done.');
    }
    public function returnBook(): void
    {
        throw new \RuntimeException('Sorry. Your request can\'t be done.');
    }
}