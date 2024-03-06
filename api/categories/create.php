<?php

include_once '../../config/Database.php';
include_once '../../models/Category.php';

//Instantiate DB/connect
$database = new Database();
$db = $database->connect();

//Instantiate new category object
$category = new Category($db);

//Get input data
$data = json_decode(file_get_contents("php://input"));

if (is_null($data->category)) {
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
    die();
}

$category->category = $data->category;

//Create category + response
echo $category->create();
