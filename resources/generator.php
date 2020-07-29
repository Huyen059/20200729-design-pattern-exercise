<?php
// this class nas nothing relevant for the exercise, it is used to generate the test data

$json = [];
if (($handle = fopen("books.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if(!isset($first)) {
            $first = false;
            continue;
        }

        [$title, $author, $genre, $pages, $publisher] = $data;
        $json[] = [
            'title' => $title,
            'author' => $author,
            'genre' => $genre,
            'pages' => $pages,
            'publisher' => $publisher,
        ];
    }
    fclose($handle);

    file_put_contents('books.json', json_encode($json, JSON_PRETTY_PRINT));
}