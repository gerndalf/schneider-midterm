<?php

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

//Instantiate DB/connect
$database = new Database();
$db = $database->connect();

//Instantiate quote object
$quote = new Quote($db);

//Grab id prop
$quote->id = isset($_GET['id']) ? $_GET['id'] : die();

//Grab quote
$quote->read_single();

//Create output array
$quote_arr = array(
    'quote' => $quote->quote
);

//Output JSON
if (count($quote_arr) > 0) {
    echo json_encode($quote_arr);
} else {
    echo json_encode(
        array('message' => 'No Quotes Found')
    );
}
