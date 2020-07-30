<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

abstract class BookImporter
{
    protected string $path;
    /**
     * @var Book[]
     */
    protected array $books = [];
    /**
     * @var Genre[]
     */
    protected array $genres = [];
    /**
     * @var Publisher[]
     */
    protected array $publishers = [];

    /**
     * BookImporter constructor.
     * @param string $path
     * @throws BooksNotImportedException
     */
    abstract public function __construct(string $path);

    /**
     * @return Book[]
     */
    public function getBooks(): array
    {
        return $this->books;
    }

    /**
     * @return Genre[]
     */
    public function getGenres(): array
    {
        return $this->genres;
    }

    /**
     * @return Publisher[]
     */
    public function getPublishers(): array
    {
        return $this->publishers;
    }

}
