<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/=json');
$method = $_SERVER['REQUEST_METHOD'];

include_once '../../config/Database.php';
include_once '../../models/Author.php';
include_once 'read.php';
include_once 'read_single.php';
include_once 'create.php';
include_once 'update.php';
include_once 'delete.php';


if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

if ($method === 'GET') {
}
