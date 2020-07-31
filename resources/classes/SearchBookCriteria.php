<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

abstract class SearchBookCriteria
{
    /**
     * SearchBook
     * @param Library $library
     * @param string $searchCriterion
     * @return Book[]
     */
    abstract public function searchBook(Library $library, string $searchCriterion): array;
}