<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

class BookImporterJson extends BookImporter {
    public function __construct(string $path)
    {
        if(!is_file($path)) {
            throw new BooksNotImportedException("Json file not found!");
        }

        $response = file_get_contents($path);
        if ($response) {
            $list = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
            foreach ($list as $item) {
                $genre = $item['genre'];
                if(!isset($this->genres[$genre])){
                    $this->genres[$genre] = new Genre($genre);
                }

                $publisher = $item['publisher'];
                if(!isset($this->publishers[$publisher])){
                    $this->publishers[$publisher] = new Publisher($publisher);
                }

                $book = new Book($item['title'],
                    str_replace(',', '', $item['author']),
                    (int)$item['pages'],
                    $this->genres[$genre],
                    $this->publishers[$publisher]);

                $this->books[] = $book;
                $this->genres[$genre]->addBook($book);
                $this->publishers[$publisher]->addBook($book);
            }
        }
    }
}

