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
    private Context $context;

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
        $this->context = new Context(new OpenState());
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

    /**
     * @return int
     */
    public function getPages(): int
    {
        return $this->pages;
    }

    /**
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
    }

    public function displayBook(): string
    {
        $genre = ucfirst($this->genre->getGenre());
        $display = "
                    <div class='col-sm-12 col-md-6 col-lg-3'>
                        <div class='card bg-light mb-3'>
                            <div class='card-header'>Author: {$this->author}</div>
                            <div class='card-body'>
                                <h5 class='card-title'>Title: {$this->title}</h5>
                                <p class='card-text'>Genre: {$genre}</p>
                                <p class='card-text'>Publisher: {$this->publisher->getPublisher()}</p>
                    ";

        $validTransactions = $this->getContext()->getState()->validTransactions();
        if (empty($validTransactions)) {
            $display .= "</div></div></div>";
            return $display;
        }

        $title = urlencode($this->title);
        $display .= "<form method='post'>";
        foreach ($validTransactions as $item) {
            switch ($item) {
                case OpenState::class:
                    $display .= "<button name='return' value='{$title}' type='submit'>Return</button>";
                    break;
                case LentState::class:
                    $display .= "<button name='borrow' value='{$title}' type='submit'>Borrow</button>";
                    break;
                case LostState::class:
                    $display .= "<button name='lost' value='{$title}' type='submit'>Report Lost</button>";
                    break;
                case SoldState::class:
                    $display .= "<button name='buy' value='{$title}' type='submit'>Buy</button>";
                    break;
            }
        }
        $display .= "</form></div></div></div>";
        return $display;
    }
}