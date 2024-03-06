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
if (is_null($data->id)) {
    echo json_encode(
        array('message' => 'category_id Not Found')
    );
}

if (is_null($data->category)) {
    echo json_encode(
        array('message' => 'Missing Required Parameters')
    );
    die();
}

//Update category
$category->id = $data->id;
$category->category = $data->category;

echo $category->update();
