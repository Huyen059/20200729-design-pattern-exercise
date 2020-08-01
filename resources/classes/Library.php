<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

class Library
{
    private string $path;
    /**
     * @var Book[]
     */
    private array $books;
    /**
     * @var Genre[]
     */
    private array $genres;
    /**
     * @var Publisher[]
     */
    private array $publishers;

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
     * @return Book[]
     * @throws Exception
     */
    public function searchBook(SearchBookCriteria $searchBook, string $searchCriterion): array
    {
        $matchedBooks = $searchBook->searchBook($this, $searchCriterion);
        if (count($matchedBooks) === 0) {
            throw new Exception('Book not found.');
        }

        return $matchedBooks;
    }

    /**
     * @param Book[] $books
     * @return int
     */
    public function getTotalPages(array $books): int
    {
        $pages = 0;


        foreach ($books as $book) {
            $state = $book->getContext()->getState();
            if($state instanceof LostState || $state instanceof SoldState) {
                continue;
            }
            $pages += $book->getPages();
        }
        return $pages;
    }

    /**
     * @param Book[]
     * @return int
     */
    public function displayTotalPages(array $books): int
    {
        return $this->getTotalPages($books);
    }

    /**
     * @param Book[] $books
     * @return string
     */
    public function displayBooks(array $books): string
    {
        $display = '';
        foreach ($books as $book) {
            $display .= $book->displayBook();
        }
        return $display;
    }

    public function displayRandomBooks(): string
    {
        $books = [];
        $randomBookIndexes = array_rand($this->books, 10);
        foreach ($randomBookIndexes as $randomBookIndex) {
            $books[] = $this->books[$randomBookIndex];
        }

        return $this->displayBooks($books);
    }
}
