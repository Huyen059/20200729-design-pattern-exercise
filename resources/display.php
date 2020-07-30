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

<form method="post">
    <label>
        <input type="text" name="name" placeholder="Enter (partial) book title">
    </label>
    <button type="submit" name="submit">Search</button>
</form>

<form method="post">
<label>Genre:
    <select name="genre">
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
</label>
<button type="submit" name="submit">Search</button>
</form>

<form method="post">
    <label>Publisher:
        <select name="publisher">
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
    </label>
    <button type="submit" name="submit">Search</button>
</form>

</body>
</html>
