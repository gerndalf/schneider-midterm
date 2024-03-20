<?php
class Author
{
    //DB Props
    private $conn;
    private $table = 'authors';

    //Obj Props
    public $id;
    public $author;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Get authors
    public function read()
    {
        $query = 'SELECT id, author FROM ' . $this->table;

        //Statement prep & execute
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    //Get single author
    public function read_single()
    {
        $query = 'SELECT id, author FROM ' . $this->table . ' WHERE id = ? LIMIT 1';

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (is_array($row) && isset($row['id']) && isset($row['author'])) {
            $this->id = $row['id'];
            $this->author = $row['author'];
        }
    }

    //Create new author
    public function create()
    {
        $query = 'INSERT INTO ' . $this->table . '(author) VALUES (:author) RETURNING id';

        $stmt = $this->conn->prepare($query);

        //Clean input data
        $this->author = htmlspecialchars(strip_tags($this->author));

        $stmt->bindParam(':author', $this->author);

        //Attempt execute
        if ($stmt->execute()) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $returnedID = $row['id'];
            return json_encode(
                array(
                    'id' => $returnedID,
                    'author' => $this->author
                )
            );
        } else {
            return json_encode(
                array('message' => 'Missing Required Parameters')
            );
        }
    }

    //Update existing author
    public function update()
    {
        $query = 'UPDATE ' . $this->table . ' SET author = :author WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        //Clean input data
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->id = htmlspecialchars(strip_tags($this->id));

        //Binding params
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':id', $this->id);

        //Attempt execute
        if ($stmt->execute() && $stmt->rowCount() > 0) {
            return json_encode(
                array(
                    'id' => $this->id,
                    'author' => $this->author
                )
            );
        } else {
            return json_encode(
                array('message' => 'No Authors Found')
            );
        }
    }

    //Delete existing category
    public function delete()
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        //Clean input data
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id);

        //Attempt execute
        if ($stmt->execute()) {
            return json_encode(
                array('id' => "$this->id")
            );
        } else {
            return json_encode(
                array('message' => 'No Authors Found')
            );
        }
    }
}
