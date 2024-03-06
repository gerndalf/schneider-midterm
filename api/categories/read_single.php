<?php

include_once '../../config/Database.php';
include_once '../../models/Category.php';

//Instantiate DB/connect
$database = new Database();
$db = $database->connect();

//Instantiate category object
$category = new Category($db);

//Grab id prop
$category->id = isset($_GET['id']) ? $_GET['id'] : die();

//Grab category
$category->read_single();

//Create output array
$category_arr = array(
    'id' => $category->id,
    'category' => $category->category
);

//Output JSON
if (count($category_arr) > 0) {
    echo json_encode($category_arr);
} else {
    echo json_encode(
        array('message' => 'category_id Not Found')
    );
}
