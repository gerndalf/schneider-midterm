<?php

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

//Instantiate DB/connect
$database = new Database();
$db = $database->connect();

//Instantiate quote object
$quote = new Quote($db);

//Handle possible inputted Foreign Keys | Will assign NULL if not inputted.
$quote->author_id = isset($_GET['author_id']) ? $_GET['author_id'] : null;
$quote->category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

//Author read query
$result = $quote->read();

//Get row count of returned quotes
$num = $result->rowCount();

if ($num > 0) {
    $quotes_array = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $single_quote = array(
            'id' => $id,
            'quote' => $quote,
            'author' => $author,
            'category' => $category
        );

        //Store item for results
        array_push($quotes_array, $single_quote);
    }

    //JSON convert + output
    echo json_encode($quotes_array);
} else {
    echo json_encode(
        array('message' => 'No Quotes Found')
    );
}
