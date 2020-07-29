<?php

declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);


const BOOK_FORMAT = 'csv';

abstract class BookImporter
{
    private string $path, $filename;

    /**
     * BookImporter constructor.
     * @param string $path
     * @param string $filename
     */
    public function __construct(string $path, string $filename)
    {
        $this->path = $path;
        $this->filename = $filename;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    abstract public function importBooks(): array;
}

class BookImporterCsv extends BookImporter
{
    public const MAX_LINE_LENGTH = 1000;

    public function importBooks(): array
    {
        $file = $this->getPath() . $this->getFilename();
        if (($handle = fopen((string)$file, 'rb'))) {
            $books = [];
            while (($data = fgetcsv($handle, self::MAX_LINE_LENGTH, ","))) {
                [$title, $author, $genre, $pages, $publisher] = $data;
                $books[] = new Book($title, $author, $genre, (int)$pages, $publisher);
            }
            fclose($handle);
        }
        array_shift($books);
        return $books;
    }
}

class BookImporterJson extends BookImporter
{
    public function importBooks(): array
    {
        $file = $this->getPath() . $this->getFilename();
        $response = @file_get_contents((string)$file);
        if ($response) {
            try {
                $list = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
                $books = [];
                foreach ($list as $item) {
                    $books[] = new Book($item['title'], $item["author"], $item["genre"], $item["pages"], $item["publisher"]);
                }
                return $books;
            } catch (JsonException $e) {
                echo $e;
            }
        }
    }
}

class Book
{
    private string $title, $author, $genre, $publisher;
    private int $pages;

    public function __construct(string $title, string $author, string $genre, int $pages, string $publisher)
    {
        $this->title = $title;
        $this->author = $author;
        $this->genre = $genre;
        $this->pages = $pages;
        $this->publisher = $publisher;
    }

}

class Library
{
    /**
     * @var Book[]
     */
    private array $books = [];

    public function importBooks(BookImporter $bookImporter): void
    {
        $this->books = $bookImporter->importBooks();
    }
}

$library = new Library();
if (BOOK_FORMAT === 'csv') {
    $library->importBooks(new BookImporterCsv("resources/", "books.csv"));
} else {
    $library->importBooks(new BookImporterJson("resources/", "books.json"));
}


echo "Hi";
