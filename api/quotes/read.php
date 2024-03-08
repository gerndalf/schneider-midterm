<?php

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

//Instantiate DB/connect
$database = new Database();
$db = $database->connect();

//Instantiate quote object
$quote = new Quote($db);

//Handle possible inputted Foreign Keys | Will assign NULL if not inputted.
$quote->author_id = $_GET['author_id'];
$quote->category_id = $_GET['category_id'];

//Author read query
$result = $quote->read();

//Get row count of returned quotes
$num = $result->rowCount();

if ($num > 0) {
    $quotes_array = array();
    $quotes_array['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $single_quote = array(
            'quote' => $quote
        );

        //Store item for results
        array_push($quotes_array['data'], $single_quote);
    }
} else {
    echo json_encode(
        array('message' => 'No Quotes Found')
    );
}
