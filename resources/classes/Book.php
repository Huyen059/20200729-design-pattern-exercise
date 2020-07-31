<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

class Book {
    private string $title, $author;
    private int $pages;
    private Genre $genre;
    private Publisher $publisher;
//    private Context $context;

    /**
     * Book constructor.
     * @param string $title
     * @param string $author
     * @param int $pages
     * @param Genre $genre
     * @param Publisher $publisher
     */
    public function __construct(string $title, string $author, int $pages, Genre $genre, Publisher $publisher)
    {
        $this->title = $title;
        $this->author = $author;
        $this->pages = $pages;
        $this->genre = $genre;
        $this->publisher = $publisher;
//        $this->context = new Context(new OpenState());
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return Genre
     */
    public function getGenre(): Genre
    {
        return $this->genre;
    }

    /**
     * @return Publisher
     */
    public function getPublisher(): Publisher
    {
        return $this->publisher;
    }
}