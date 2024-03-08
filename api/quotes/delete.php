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

$quote->id = $data->id;

//Delete quote
echo $quote->delete();
