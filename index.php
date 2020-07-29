<?php

declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);


const BOOK_FORMAT = 'csv';

abstract class BookImporter
{
    public const MAX_LINE_LENGTH = 1000;
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
                $books[] = new Book($data[0], $data[1], $data[2], $data[3], $data[4]);
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
    private string $title, $author, $genre, $pages, $publisher;

    public function __construct(string $title = '', string $author = '', string $genre = '', string $pages = '', string $publisher = '')
    {
        $this->title = $title;
        $this->author = $author;
        $this->genre = $genre;
        $this->pages = $pages;
        $this->publisher = $publisher;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getGenre(): string
    {
        return $this->genre;
    }

    /**
     * @param string $genre
     */
    public function setGenre(string $genre): void
    {
        $this->genre = $genre;
    }

    /**
     * @return string
     */
    public function getPages(): string
    {
        return $this->pages;
    }

    /**
     * @param string $pages
     */
    public function setPages(string $pages): void
    {
        $this->pages = $pages;
    }

    /**
     * @return string
     */
    public function getPublisher(): string
    {
        return $this->publisher;
    }

    /**
     * @param string $publisher
     */
    public function setPublisher(string $publisher): void
    {
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
