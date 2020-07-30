<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

class Library {
    private string $path;
    /**
     * @var Book[]
     */
    private array $books = [];
    /**
     * @var Genre[]
     */
    private array $genres = [];
    /**
     * @var Publisher[]
     */
    private array $publishers = [];

    /**
     * Library constructor.
     * @param BookImporter $importer
     */
    public function __construct(BookImporter $importer)
    {
        $this->books = $importer->getBooks();
        $this->genres = $importer->getGenres();
        $this->publishers = $importer->getPublishers();
    }

    /**
     * @return Book[]
     */
    public function getBooks(): array
    {
        return $this->books;
    }

    /**
     * @return Genre[]
     */
    public function getGenres(): array
    {
        return $this->genres;
    }

    /**
     * @return Publisher[]
     */
    public function getPublishers(): array
    {
        return $this->publishers;
    }

    /**
     * @param SearchBook $searchBook
     * @param string $searchCriterion
     * @return Book[]
     */
    public function searchBook(SearchBook $searchBook, string $searchCriterion): array
    {
        // Todo: throw error when book not found
        $matchedBooks = $searchBook->searchBook($this, $searchCriterion);
        if(count($matchedBooks) === 0){
            throw new Exception('Book not found.');
        }

        return $matchedBooks;
    }
}
