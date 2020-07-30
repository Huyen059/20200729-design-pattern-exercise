<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

session_start();

const BOOK_FORMAT = 'json';

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
                $author = str_replace(',', '', $author);
                $books[] = new Book($title, $author, $genre, (int)$pages, $publisher, new OpenState());
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
                    $books[] = new Book($item['title'], str_replace(',', '', $item['author']), $item['genre'], (int)$item['pages'], $item['publisher'], new OpenState());
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
    private Context $context;

    public function __construct(string $title, string $author, string $genre, int $pages, string $publisher, State $state)
    {
        $this->title = $title;
        $this->author = $author;
        $this->genre = $genre;
        $this->pages = $pages;
        $this->publisher = $publisher;
        $this->context = new Context($state);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function getPublisher(): string
    {
        return $this->publisher;
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function setContext(Context $context): void
    {
        $this->context = $context;
    }

    public function displayBook(): string
    {
        $state = $this->context->getState();
        $status = get_class($state);
        $title = urlencode($this->getTitle());

        $display = "
        <div>
            <h3>{$this->getTitle()}</h3>
            <p>Author: {$this->getAuthor()}</p>
            <p>Genre: {$this->getGenre()}</p>
            <p>Pages: {$this->getPages()}</p>
            <p>Publisher: {$this->getPublisher()}</p>
            <p>Status: {$status}</p>
        </div>
        ";
        switch ($status) {
            case 'OpenState':
                $display .= "<div>
                    <a href=\"?title={$title}&state=lent\">Borrow</a>
                    <a href=\"?title={$title}&state=sold\">Buy</a></div>";
                break;
            case 'LentState':
                $display .= "<div>
                    <a href=\"?title={$title}&state=lost\">Report lost</a>
                    <a href=\"?title={$title}&state=open\">Return</a></div>";
                break;
            case 'LostState':
            case 'SoldState':
                $display = '';
                break;
        }

        return $display;
    }

}

interface SearchBookCriteria {
    /**
     * @param Library $library
     * @param string $searchCriterion
     * @return Book[]
     */
    public function searchBooks(Library $library, string $searchCriterion) : array;
}

class PartialTitleSearch implements SearchBookCriteria {
    /**
     * @param Library $library
     * @param string $searchCriterion
     * @return Book[]
     */
    public function searchBooks(Library $library, string $searchCriterion): array
    {
        // TODO: Implement searchBooks() method.
        $matchedBooks = [];
        foreach ($library->getBooks() as $book) {
            if (stripos($book->getTitle(), $searchCriterion) !== false) {
                $matchedBooks[] = $book;
            }
        }
        return $matchedBooks;
    }
}

class GenreSearch implements SearchBookCriteria {
    /**
     * @param Library $library
     * @param string $searchCriterion
     * @return Book[]
     */
    public function searchBooks(Library $library, string $searchCriterion): array
    {
        // TODO: Implement searchBooks() method.
        $matchedBooks = [];
        foreach ($library->getBooks() as $book) {
            if ($book->getGenre() === $searchCriterion) {
                $matchedBooks[] = $book;
            }
        }
        return $matchedBooks;
    }
}

class PublisherSearch implements SearchBookCriteria {
    /**
     * @param Library $library
     * @param string $searchCriterion
     * @return Book[]
     */
    public function searchBooks(Library $library, string $searchCriterion): array
    {
        // TODO: Implement searchBooks() method.
        $matchedBooks = [];
        foreach ($library->getBooks() as $book) {
            if ($book->getPublisher() === $searchCriterion) {
                $matchedBooks[] = $book;
            }
        }
        return $matchedBooks;
    }
}

class Context {
    /**
     * @var State;
     */
    private $state;

    /**
     * CurrentState constructor.
     * @param State $state
     */
    public function __construct(State $state)
    {
        $this->transitionTo($state);
    }

    /**
     * @return State
     */
    public function getState(): State
    {
        return $this->state;
    }

    public function transitionTo(State $state): void
    {
        $this->state = $state;
        $this->state->setContext($this);
    }

    public function borrow(): void
    {
        $this->state->borrowBook();
    }

    public function buy(): void
    {
        $this->state->buyBook();
    }

    public function reportLost(): void
    {
        $this->state->setBookToLost();
    }

    public function setOvertime(): void
    {
        $this->state->setBookToOvertime();
    }

    public function return(): void
    {
        $this->state->returnBook();
    }
}

abstract class State {
    /**
     * @var Context
     */
    protected Context $context;

    /**
     * @param Context $context
     */
    public function setContext(Context $context): void
    {
        $this->context = $context;
    }
    public function isVisible():bool
    {
        switch (get_class($this)){
            case 'OpenState':
            case 'LentState':
            case 'OvertimeState':
                return true;
            case 'LostState':
            case 'SoldState':
                return false;
        }
    }
    public function borrowBook(): void
    {
        throw new \RuntimeException('Sorry. Your request can\'t be done.');
    }
    public function buyBook(): void
    {
        throw new \RuntimeException('Sorry. Your request can\'t be done.');
    }
    public function setBookToLost(): void
    {
        throw new \RuntimeException('Sorry. Your request can\'t be done.');
    }
    public function setBookToOvertime(): void
    {
        throw new \RuntimeException('Sorry. Your request can\'t be done.');
    }
    public function returnBook(): void
    {
        throw new \RuntimeException('Sorry. Your request can\'t be done.');
    }
}

class OpenState extends State {
    public function borrowBook(): void
    {
        $this->context->transitionTo(new LentState());
    }

    public function buyBook(): void
    {
        $this->context->transitionTo(new SoldState());
    }
}

class LentState extends State {
    public function setBookToLost(): void
    {
        $this->context->transitionTo(new LostState());
    }

    public function setBookToOvertime(): void
    {
        $this->context->transitionTo(new OvertimeState());
    }

    public function returnBook(): void
    {
        $this->context->transitionTo(new OpenState());
    }
}

class OvertimeState extends State {
    public function setBookToLost(): void
    {
        $this->context->transitionTo(new LostState());
    }

    public function returnBook(): void
    {
        $this->context->transitionTo(new OpenState());
    }
}

class LostState extends State {
}

class SoldState extends State {
}

class Library
{
    /**
     * @var Book[]
     */
    protected array $books = [];

    public function getBooks(): array
    {
        return $this->books;
    }

    public function importBooks(BookImporter $bookImporter): void
    {
        $this->books = $bookImporter->importBooks();
    }

    /**
     * @param SearchBookCriteria $searchBookCriteria
     * @param string $searchCriterion
     * @return Book[]
     */
    public function searchBooks(SearchBookCriteria $searchBookCriteria, string $searchCriterion): array
    {
        return $searchBookCriteria->searchBooks($this, $searchCriterion);
    }

    public static function searchABook(Library $library, string $title): Book
    {
        // @Todo: make function here
        foreach ($library->getBooks() as $book) {
            if ($title === $book->getTitle()) {
                return $book;
            }
        }
    }

    /**
     * @param Book[]
     * @return int
     */
    public static function getPages (array $books): int
    {
        $pages = 0;
        foreach ($books as $book) {
            if($book->getContext()->getState()->isVisible()){
                $pages += $book->getPages();
            }
        }
        return $pages;
    }
}

if (isset($_SESSION['library'])) {
    $library = $_SESSION['library'];
} else {
    $library = new Library();
    if (BOOK_FORMAT === 'csv') {
        $library->importBooks(new BookImporterCsv("resources/", "books.csv"));
    } else {
        $library->importBooks(new BookImporterJson("resources/", "books.json"));
    }
    $_SESSION['library'] = $library;
}

if (isset($_POST['name'])) {
    $searchText = htmlspecialchars(trim($_POST['name']));
    $matchedBooks = $library->searchBooks(new PartialTitleSearch(), $searchText);
}

if (isset($_POST['genre'])) {
    $searchText = $_POST['genre'];
    $matchedBooks = $library->searchBooks(new GenreSearch(), $searchText);
}

if (isset($_POST['publisher'])) {
    $searchText = $_POST['publisher'];
    $matchedBooks = $library->searchBooks(new PublisherSearch(), $searchText);
}

if (isset($_GET['state'])){
    $book = Library::searchABook($library, $_GET['title']);
    $context = $book->getContext();
    switch ($_GET['state']) {
        case 'lent':
            $context->borrow();
            break;
        case 'sold':
            $context->buy();
            break;
        case 'open':
            $context->return();
            break;
        case 'lost':
            $context->reportLost();
            break;
    }
    $book->setContext($context);
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Library</title>
</head>
<body>

<?php
if(!empty($_POST['name']) || !empty($_POST['genre']) || !empty($_POST['publisher'])) {
    echo "Total number of pages: " . Library::getPages($matchedBooks);
} else {
    echo "Total number of pages: " . Library::getPages($library->getBooks());
}
?>

<form action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
    <label>Search for books:<input type="text" name="name" placeholder="Name/partial name"></label>
    <button type="submit" name="submit">Search</button>
</form>

<form action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
<label>Genre:
    <select name="genre">
        <option disabled selected value="">Choose a genre</option>
        <?php
        $genres = [];
        foreach ($library->getBooks() as $book) {
            $genres[] = $book->getGenre();
        }
        foreach (array_unique($genres) as $genre) {
            $option = "<option value=\"{$genre}\" ";
            if(isset($_POST['genre']) && $_POST['genre'] === $genre) {
                $option .= "selected";
            }
            $option .= ">{$genre}</option>";
            echo $option;
        }
        ?>
    </select>
</label>
<button type="submit" name="submit">Search</button>
</form>

<form action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
    <label>Genre:
        <select name="publisher">
            <option disabled selected value="">Choose a publisher</option>
            <?php
            $publishers = [];
            foreach ($library->getBooks() as $book) {
                $publishers[] = $book->getPublisher();
            }
            foreach (array_unique($publishers) as $publisher) {
                $option = "<option value=\"{$publisher}\" ";
                if(isset($_POST['publisher']) && $_POST['publisher'] === $publisher) {
                    $option .= "selected";
                }
                $option .= ">{$publisher}</option>";
                echo $option;
            }
            ?>
        </select>
    </label>
    <button type="submit" name="submit">Search</button>
</form>


<?php
if (isset($matchedBooks) && !empty($matchedBooks)) {
    foreach ($matchedBooks as $book) {
            echo $book->displayBook();

    }
}
?>

</body>
</html>
