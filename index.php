<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);


const BOOK_FORMAT = 'json';
const RESOURCES_BOOKS_CSV = 'resources/books.csv';
const RESOURCES_BOOKS_JSON = 'resources/books.json';

class BooksNotImportedException extends Exception {}

require 'resources/classes/BookImporter.php';
require 'resources/classes/BookImporterJson.php';
require 'resources/classes/BookImporterCsv.php';
require 'resources/classes/Book.php';
require 'resources/classes/Genre.php';
require 'resources/classes/Publisher.php';
require 'resources/classes/SearchBookCriteria.php';
require 'resources/classes/PartialBookSearch.php';
require 'resources/classes/GenreSearch.php';
require 'resources/classes/PublisherSearch.php';
require 'resources/classes/Context.php';
require 'resources/classes/State.php';
require 'resources/classes/OpenState.php';
require 'resources/classes/LentState.php';
require 'resources/classes/OvertimeState.php';
require 'resources/classes/LostState.php';
require 'resources/classes/SoldState.php';
require 'resources/classes/Library.php';
session_start();

if (isset($_SESSION['library'])) {
    /**
     * @var Library $library
     */
    $library = $_SESSION['library'];
} else if(BOOK_FORMAT === 'json') {
    $library = new Library(new BookImporterJson(RESOURCES_BOOKS_JSON));
    $_SESSION['library'] = $library;
} else {
    $library = new Library(new BookImporterCsv(RESOURCES_BOOKS_CSV));
    $_SESSION['library'] = $library;
}

$library->manageOvertimeBook();


$matchBooks = [];
if (isset($_POST['name'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    try {
        $matchBooks = $library->searchBook(new PartialBookSearch(), $name);
    } catch (Exception $e) {
        throw new Exception('Book not found!');
    }
}

if (isset($_POST['genre'])){
    $genre = htmlspecialchars(trim($_POST['genre']));
    try {
        $matchBooks = $library->searchBook(new GenreSearch(), $genre);
    } catch (Exception $e) {
        throw new Exception('Book not found!');
    }
}

if (isset($_POST['publisher'])){
    $publisher = htmlspecialchars(trim($_POST['publisher']));
    try {
        $matchBooks = $library->searchBook(new PublisherSearch(), $publisher);
    } catch (Exception $e) {
        throw new Exception('Book not found!');
    }
}

if (isset($_POST[Book::BORROW])){
    $title = htmlspecialchars(trim(urldecode($_POST[Book::BORROW])));
    $books = $library->searchBook(new PartialBookSearch(), $title);
    if(count($books) > 1) {
        throw new Exception("More than one books have the same title.");
    }
    $books[0]->getContext()->borrow();
}

if (isset($_POST[Book::BUY])){
    $title = htmlspecialchars(trim(urldecode($_POST[Book::BUY])));
    $books = $library->searchBook(new PartialBookSearch(), $title);
    if(count($books) > 1) {
        throw new Exception("More than one books have the same title.");
    }
    $books[0]->getContext()->buy();
}

if (isset($_POST[Book::LOST])){
    $title = htmlspecialchars(trim(urldecode($_POST[Book::LOST])));
    $books = $library->searchBook(new PartialBookSearch(), $title);
    if(count($books) > 1) {
        throw new Exception("More than one books have the same title.");
    }
    $books[0]->getContext()->reportLost();
}

if (isset($_POST[Book::RETURN])){
    $title = htmlspecialchars(trim(urldecode($_POST[Book::RETURN])));
    $books = $library->searchBook(new PartialBookSearch(), $title);
    if(count($books) > 1) {
        throw new Exception("More than one books have the same title.");
    }
    $books[0]->getContext()->return();
}

require 'resources/display.php';

