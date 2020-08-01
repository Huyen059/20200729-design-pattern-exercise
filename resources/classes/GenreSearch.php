<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

class GenreSearch extends SearchBookCriteria
{
    /**
     * @param Library $library
     * @param string $searchCriterion
     * @return Book[]
     */
    public function searchBook(Library $library, string $searchCriterion): array
    {
        foreach ($library->getGenres() as $genre) {
            if(htmlspecialchars($genre->getGenre()) === $searchCriterion) {
                return $genre->getBooks();
            }
        }
    }
}