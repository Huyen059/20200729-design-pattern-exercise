<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

class Genre {
    /**
     * @var Book[]
     */
    private array $books = [];
    private string $genre;

    /**
     * Genre constructor.
     * @param string $genre
     */
    public function __construct(string $genre)
    {
        $this->genre = $genre;
    }

    public function addBook(Book $book): void
    {
        $this->books[] = $book;
    }

    /**
     * @return string
     */
    public function getGenre(): string
    {
        return $this->genre;
    }

    /**
     * @return Book[]
     */
    public function getBooks(): array
    {
        return $this->books;
    }

}
