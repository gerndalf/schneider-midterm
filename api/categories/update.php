<?php

include_once '../../config/Database.php';
include_once '../../models/Category.php';

//Instantiate DB/connect
$databse = new Database();
$db = $databse->connect();

//Instantiate category object
$category = new Category($db);

//Get input data
$data = json_decode(file_get_contents("php://input"));

//Check for required params
if (!isset($data->id) || !isset($data->category)) {
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
    die();
}

//Update category
$category->id = $data->id;
$category->category = $data->category;

echo $category->update();
