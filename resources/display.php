<?php
declare(strict_types=1);
ini_set('display_errors', "1");
ini_set('display_startup_errors', "1");
error_reporting(E_ALL);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Library</title>
</head>
<body>
<header class="my-5">
    <h1 class="text-center">Library</h1>
</header>
<div class="container">
    <div class="d-flex flex-column align-items-center">

    <div>
        <div class="mb-4">
            <form method="post">
                <div class="input-group mb-3">
                    <input type="text" name="name" class="form-control" placeholder="Enter (partial) book title">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" name="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="mb-4">
            <form method="post">
                <div class="input-group">
                    <select class="custom-select" name="genre" id="inputGroupSelect04">
                        <option disabled selected value="">Choose a genre</option>

                        <?php
                        /**
                         * @var Library $library
                         */
                        foreach ($library->getGenres() as $genre) {
                            $option = '';
                            $option .= "<option value='{$genre->getGenre()}' ";
                            if(isset($_POST['genre']) && $_POST['genre'] === $genre->getGenre()) {
                                $option .= "selected";
                            }
                            $option .= ">".ucfirst($genre->getGenre())."</option>";
                            echo $option;
                        }
                        ?>
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" name="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="mb-4">
            <form method="post">
                <div class="input-group">
                    <select class="custom-select" name="publisher" id="inputGroupSelect04">
                        <option disabled selected value="">Choose a publisher</option>

                        <?php
                        foreach ($library->getPublishers() as $publisher) {
                            $option = "<option value=\"{$publisher->getPublisher()}\" ";
                            if(isset($_POST['publisher']) && $_POST['publisher'] === $publisher->getPublisher()) {
                                $option .= "selected";
                            }
                            $option .= ">". ucfirst($publisher->getPublisher()) . "</option>";
                            echo $option;
                        }
                        ?>
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" name="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

        <div class="my-5">
            <?php
            if(!empty($matchBooks)){
                echo "<div><h5>Total number of pages of this search: {$library->displayTotalPages($matchBooks)}</h5></div>";
            } else {
                echo "<div><h5>Total number of pages in this library: {$library->displayTotalPages($library->getBooks())}</h5></div>";
            }
            ?>
        </div>

        <div class="row">
            <?php
            if(!empty($matchBooks)){
                echo "<h3 class='mx-auto mb-5'>Search results:</h3>";
                echo $library->displayBooks($matchBooks);
            } else {
                echo "<h3 class='mx-auto mb-5'>Some books in our library:</h3>";
                echo $library->displayRandomBooks();
            }
            ?>
        </div>

    </div>
</div>
</body>
</html>
