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
     * @var Book[]
     */
    private array $matchedBooks = [];
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
     * @param SearchBookCriteria $searchBook
     * @param string $searchCriterion
     * @throws Exception
     */
    public function searchBook(SearchBookCriteria $searchBook, string $searchCriterion): void
    {
        $matchedBooks = $searchBook->searchBook($this, $searchCriterion);
        if(count($matchedBooks) === 0){
            throw new Exception('Book not found.');
        }

        $this->matchedBooks = $matchedBooks;
    }

    /**
     * @param Book[] $books
     * @return int
     */
    public function getTotalPages(array $books): int
    {
        $pages = 0;
        foreach ($books as $book) {
            $pages += $book->getPages();
        }
        return $pages;
    }

    public function displayTotalPages(): string
    {
        if(count($this->matchedBooks) !== 0) {
            return "
            <div><h5>Total number of pages for this search: {$this->getTotalPages($this->matchedBooks)}</h5></div>
            ";
        }

        return "
            <div><h5>Total number of pages in library: {$this->getTotalPages($this->books)}</h5></div>
        ";
    }

    public function displayBooks(): string
    {
        $display = '';
        if(count($this->matchedBooks) !== 0) {
            $books = $this->matchedBooks;
        } else {
            $books = [];
            $randomBookIndexes = array_rand($this->books, 10);
            foreach ($randomBookIndexes as $randomBookIndex) {
                $books[] = $this->books[$randomBookIndex];
            }
        }
        foreach ($books as $book) {
            $display .= $book->displayBook();
        }
        return $display;
    }
}
