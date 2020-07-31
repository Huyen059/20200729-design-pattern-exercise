<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

class PublisherSearch extends SearchBookCriteria
{
    /**
     * @param Library $library
     * @param string $searchCriterion
     * @return Book[]
     */
    public function searchBook(Library $library, string $searchCriterion): array
    {
        foreach ($library->getPublishers() as $publisher) {
            if($publisher->getPublisher() === $searchCriterion) {
                return $publisher->getBooks();
            }
        }
    }
}