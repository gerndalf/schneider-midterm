<?php

include_once '../../config/Database.php';
include_once '../../models/Author.php';

//Instantiate DB/connect
$database = new Database();
$db = $database->connect();

//Instantiate new author object
$author = new Author($db);

//Get input data
$data = json_decode(file_get_contents("php://input"));

if (is_null($data->author)) {
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
    die();
}

$author->author = $data->author;

//Create author + response
echo $author->create();
