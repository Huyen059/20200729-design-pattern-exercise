<?php

declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

class BookImporterCsv extends BookImporter
{
    public const MAX_LINE_LENGTH = 1000;
    public function __construct(string $path)
    {
        if (!is_file($path)) {
            throw new BooksNotImportedException("Csv file not found!");
        }

        $handle = fopen((string)$path, 'rb');
        while (($data = fgetcsv($handle, self::MAX_LINE_LENGTH, ","))) {
            if(!isset($first)) {
                $first = false;
                continue;
            }

            [$title, $author, $genre, $pages, $publisher] = $data;
            if (!isset($this->genres[$genre])) {
                $this->genres[$genre] = new Genre($genre);
            }

            if (!isset($this->publishers[$publisher])) {
                $this->publishers[$publisher] = new Publisher($publisher);
            }

            $book = new Book($title,
                str_replace(',', '', $author),
                (int)$pages,
                $this->genres[$genre],
                $this->publishers[$publisher]);
            $this->books[] = $book;
            $this->genres[$genre]->addBook($book);
            $this->publishers[$publisher]->addBook($book);
        }
        fclose($handle);
    }
}

