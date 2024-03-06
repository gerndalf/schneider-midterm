<?php

include_once '../../config/Database.php';
include_once '../../models/Author.php';

//Instantiate DB/connect
$database = new Database();
$db = $database->connect();

//Instantiate author object
$author = new Author($db);

//Author read query
$result = $author->read();

//Get row count of returned authors
$num = $result->rowCount();

//Check for existing authors
if ($num > 0) {
    $author_array = array();
    $author_array['data'] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $single_author = array(
            'id' => $id,
            'author' => $author
        );

        //Store item for results
        array_push($author_array['data'], $single_author);
    }

    //JSON convert + output
    echo json_encode($author_array);
} else {
    //No authors response
    echo json_encode(
        array('message' => 'No Authors Found')
    );
}
