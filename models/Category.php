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

    //Get single category
    public function read_single()
    {
        $query = 'SELECT id, category FROM ' . $this->table . ' WHERE id = ? LIMIT 1';

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (is_array($row) && isset($row['id']) && isset($row['category'])) {
            $this->id = $row['id'];
            $this->category = $row['category'];
        }
    }

    //Create new category
    public function create()
    {
        $query = 'INSERT INTO ' . $this->table . '(category) VALUES (:category) RETURNING id';

        $stmt = $this->conn->prepare($query);

        //Clean input data
        $this->category = htmlspecialchars(strip_tags($this->category));

        $stmt->bindParam(':category', $this->category);

        //Attempt execute
        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $returnedID = $row['id'];
            return json_encode(
                array(
                    'id' => $returnedID,
                    'category' => $this->category
                )
            );
        } else {
            return json_encode(
                array('message' => 'Missing Required Parameters')
            );
        }
    }

    //Update existing category
    public function update()
    {
        $query = 'UPDATE ' . $this->table . ' SET category = :category WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        //Clean input data
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->id = htmlspecialchars(strip_tags($this->id));

        //Binding params
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':id', $this->id);

        //Attempt execute
        if ($stmt->execute() && $stmt->rowCount() > 0) {
            return json_encode(
                array(
                    'id' => $this->id,
                    'category' => $this->category
                )
            );
        } else {
            return json_encode(
                array('message' => 'No Categories Found')
            );
        }
    }

    //Delete existing category
    public function delete()
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE  id = :id';

        $stmt = $this->conn->prepare($query);

        //Clean input data
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id);

        //Attempt execute
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return json_encode(
                    array('id' => "$this->id")
                );
            } else {
                return json_encode(
                    array('message' => 'No Categories Found')
                );
            }
        } else {
            return json_encode(
                array('message' => 'Query Failed')
            );
        }
    }
}
