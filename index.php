<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

session_start();

const BOOK_FORMAT = 'json';
class BooksNotImportedException extends Exception {}

require 'resources/classes/BookImporter.php';
require 'resources/classes/BookImporterJson.php';
require 'resources/classes/BookImporterCsv.php';
require 'resources/classes/Book.php';
require 'resources/classes/Genre.php';
require 'resources/classes/Publisher.php';
require 'resources/classes/SearchBook.php';
require 'resources/classes/PartialBookSearch.php';
require 'resources/classes/Library.php';

if (isset($_SESSION['library'])) {
    /**
     * @var Library $library
     */
    $library = $_SESSION['library'];
} else {
    $library = new Library(new BookImporterCsv('resources/books.csv'));
}

if (isset($_POST['name'])) {

}

require 'resources/display.php';

