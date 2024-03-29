<?php

include_once '../../config/Database.php';
include_once '../../models/Author.php';

//Instantiate DB/connect
$databse = new Database();
$db = $databse->connect();

//Instantiate author object
$author = new Author($db);

//Get input data
$data = json_decode(file_get_contents("php://input"));

//Check for required params
if (!isset($data->id) || !isset($data->author)) {
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
    die();
}

//Update author
$author->id = $data->id;
$author->author = $data->author;

echo $author->update();
