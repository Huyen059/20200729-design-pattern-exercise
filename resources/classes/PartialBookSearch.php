<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

class PartialBookSearch extends SearchBookCriteria {
    /**
     * SearchBook
     * @param Library $library
     * @param string $searchCriterion
     * @return Book[]
     */
    public function searchBook(Library $library, string $searchCriterion): array
    {
        $matchedBooks = [];
        foreach($library->getBooks() as $book) {
            if(stripos($book->getTitle(), $searchCriterion) !== false) {
                $matchedBooks[] = $book;
            }
        }
        return $matchedBooks;
    }
}
