<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

if ($method === 'GET' && isset($_GET['id'])) {
    //Read single quote
    include_once 'read_single.php';
} elseif ($method === 'GET') {
    //Read all quotes
    include_once 'read.php';
}

if ($method === 'POST') {
    //Create new quote
    include_once 'create.php';
}

if ($method === 'PUT') {
    //Update quote entry
    include_once 'update.php';
}

if ($method === 'DELETE') {
    //Delete quote entry
    include_once 'delete.php';
}
