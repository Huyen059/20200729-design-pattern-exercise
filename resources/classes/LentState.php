<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

class LentState extends State {
    public const LEND_PERIOD_IN_SECONDS = 1 * 60;
    private DateTime $expire;

    /**
     * @return DateTime
     */
    public function getExpire(): DateTime
    {
        return $this->expire;
    }



    public function setTimer(): void
    {
        $start = new DateTime();
        $this->expire = $start->add(new DateInterval('PT'.self::LEND_PERIOD_IN_SECONDS.'S'));
    }

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