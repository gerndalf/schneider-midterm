<?php

include_once './config/Database.php';
include_once './models/Category.php';

//Instantiate DB/connect
$database = new Database();
$db = $database->connect();

//Instantiate category object
$category = new Category($db);

//Category read query
$result = $category->read();

//Get row count of returned categories
$num = $result->rowCount();

//Check for existing categories
if ($num > 0) {
    $category_array = array();
    $category_array['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $single_category = array(
            'id' => $id,
            'category' => $category
        );

        //Store item for results
        array_push($category_array['data'], $single_category);
    }

    //JSON convert + output
    echo json_encode($category_array);
} else {
    //No categories response
    echo json_encode(
        array('message' => 'No Categories Found')
    );
}
