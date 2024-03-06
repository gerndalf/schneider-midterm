<?php

include_once './config/Database.php';
include_once './models/Category.php';

//Instantiate DB/connect
$database = new Database();
$db = $database->connect();

//Instantiate category object
$category = new Category($db);

//Get input data
$data = json_decode(file_get_contents("php://input"));

$category->id = $data->id;

//Delete category
echo $category->delete();
