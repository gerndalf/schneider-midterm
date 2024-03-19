<?php

include_once '../../config/Database.php';
include_once '../../models/Author.php';

//Instantiate DB/connect
$database = new Database();
$db = $database->connect();

//Instantiate author object
$author = new Author($db);

//Grab id prop
$author->id = isset($_GET['id']) ? $_GET['id'] : die();

//Grab author
$author->read_single();

//Create output array
$author_arr = array(
    'id' => $author->id,
    'author' => $author->author
);

//Output JSON
if (is_null($author_arr['author'])) {
    echo json_encode(
        array('message' => 'author_id Not Found')
    );
} else {
    echo json_encode($author_arr);
}
