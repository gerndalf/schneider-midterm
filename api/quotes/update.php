<?php

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

//Instantiate DB/connect
$database = new Database();
$db = $database->connect();

//Instantiate quote object
$quote = new Quote($db);

//Get input data
$data = json_decode(file_get_contents("php://input"));

//Check for required params
if (is_null($data->id) || is_null($data->quote) || is_null($data->author_id) || is_null($data->category_id)) {
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
    die();
}

//Update quote
$quote->id = $data->id;
$quote->quote = $data->quote;
$quote->author_id = $data->author_id;
$quote->category_id = $data->category_id;

echo $quote->update();
