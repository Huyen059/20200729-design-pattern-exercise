<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

class PartialBookSearch extends SearchBook {
    /**
     * @param Library $library
     * @param string $name
     * @return Book[]
     */
    public function searchBook(Library $library, string $name): array
    {
        $matchedBooks = [];
        foreach($library->getBooks() as $book) {
            if($book->getTitle() === $name) {
                $matchedBooks[] = $book;
            }
        }
        $this->books = $matchedBooks;
        return $matchedBooks;
    }

    public function count()
    {
        // TODO: Implement count() method.
    }
}
