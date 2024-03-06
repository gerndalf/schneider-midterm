<?php

include_once '../../config/Database.php';
include_once '../../models/Author.php';

//Instantiate DB/connect
$database = new Database();
$db = $database->connect();

//Instantiate author object
$author = new Author($db);

//Get input data
$data = json_decode(file_get_contents("php://input"));

$author->id = $data->id;

//Delete author
echo $author->delete();
