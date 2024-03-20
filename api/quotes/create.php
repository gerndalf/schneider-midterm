<?php

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

//Instantiate DB/connect
$database = new Database();
$db = $database->connect();

//Instantiate new quote object
$quote = new Quote($db);

//Get input date
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)) {
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
    die();
}

$quote->quote = $data->quote;
$quote->author_id = $data->author_id;
$quote->category_id = $data->category_id;

//Create quote + response
echo $quote->create();
