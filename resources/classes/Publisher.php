<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

class Publisher {
    /**
     * @var Book[]
     */
    private array $books = [];
    private string $publisher;

    /**
     * Genre constructor.
     * @param string $publisher
     */
    public function __construct(string $publisher)
    {
        $this->publisher= $publisher;
    }

    public function addBook(Book $book): void
    {
        $this->books[] = $book;
    }

    /**
     * @return string
     */
    public function getPublisher(): string
    {
        return $this->publisher;
    }
}
