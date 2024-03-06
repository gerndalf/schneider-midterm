<?php
class Category
{
    // DB Props
    private $conn;
    private $table = 'categories';

    //Obj Props
    public $id;
    public $category;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Get categories
    public function read()
    {
        $query = 'SELECT id, category FROM ' . $this->table;

        //Statement prep & execute
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
}
