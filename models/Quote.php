<?php
class Quote
{
    //DB Props
    private $conn;
    private $table = 'quotes';

    //Obj Props
    public $id;
    public $quote;
    public $author;
    public $author_id;
    public $category;
    public $category_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Get quotes depending on ID inputs
    public function read()
    {
        $query = '
            SELECT quotes.id, quotes.quote, authors.author, categories.category
            FROM ' . $this->table . '
            JOIN authors
            ON quotes.author_id = authors.id
            JOIN categories
            ON quotes.category_id = categories.id';
        $bindings = [];

        //Statement prep & execute
        //Determine proper query / params to bind
        if (!is_null($this->author_id) && !is_null($this->category_id)) {
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
            $query .= ' WHERE author_id = ? AND category_id = ?';
            array_push($bindings, $this->author_id);
            array_push($bindings, $this->category_id);
        } elseif (!is_null($this->author_id)) {
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $query .= ' WHERE author_id = ?';
            array_push($bindings, $this->author_id);
        } elseif (!is_null($this->category_id)) {
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
            $query .= ' WHERE category_id = ?';
            array_push($bindings, $this->category_id);
        }

        $stmt = $this->conn->prepare($query);

        //Bind all params
        if (count($bindings) > 0) {
            foreach ($bindings as $key => $value) {
                $stmt->bindValue($key + 1, $value);
            }
        }
        $stmt->execute();

        return $stmt;
    }

    //Get single quote
    public function read_single()
    {
        $query = '
            SELECT quotes.id, quotes.quote, authors.author, categories.category 
            FROM ' . $this->table . '
            JOIN authors
            ON quotes.author_id = authors.id
            JOIN categories
            ON quotes.category_id = categories.id
            WHERE quotes.id = ? 
            LIMIT 1';

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (is_array($row) && isset($row['id']) && isset($row['quote']) && isset($row['author']) && isset($row['category'])) {
            $this->id = $row['id'];
            $this->quote = $row['quote'];
            $this->author = $row['author'];
            $this->category = $row['category'];
        }
    }

    //Create new quote
    public function create()
    {
        $query = 'INSERT INTO ' . $this->table . '(quote, author_id, category_id) VALUES (:quote, :author_id, :category_id) RETURNING id';

        $stmt = $this->conn->prepare($query);

        //Clean input data
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);

        //Attempt execute
        try {
            if ($stmt->execute()) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $returnedID = $row['id'];
                return json_encode(
                    array(
                        'id' => $returnedID,
                        'quote' => $this->quote,
                        'author_id' => $this->author_id,
                        'category_id' => $this->category_id
                    )
                );
            } else {
                return json_encode(
                    array('message' => 'Missing Required Parameters')
                );
            }
        } catch (Exception $e) {
            //Check for foreign key failure state
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
            if ($errorCode === '23503') { //23503 is the state error for foreign key violations
                //stripos() will return false if passed string does not contain indicated phrase
                if (stripos($errorMessage, 'author_id') !== false) {
                    return json_encode(
                        array('message' => 'author_id Not Found')
                    );
                } elseif (stripos($errorMessage, 'category_id') !== false) {
                    return json_encode(
                        array('message' => 'category_id Not Found')
                    );
                } else {
                    return json_encode(
                        array('message' => 'unknown foreign key error occurred')
                    );
                }
            } else {
                return json_encode(
                    array('message' => 'Missing Required Parameters')
                );
            }
        }
    }

    //Update existing quote
    public function update()
    {
        $query = 'UPDATE ' . $this->table . ' SET ';
        $newProps = [];
        $bindings = [];

        if (!is_null($this->quote)) {
            array_push($newProps, 'quote = ?');
            array_push($bindings, $this->quote);
        }
        if (!is_null($this->author_id)) {
            array_push($newProps, 'author_id = ?');
            array_push($bindings, $this->author_id);
        }
        if (!is_null($this->category_id)) {
            array_push($newProps, 'category_id = ?');
            array_push($bindings, $this->category_id);
        }

        $query .= implode(", ", $newProps);
        $query .= ' WHERE id = ?';
        array_push($bindings, $this->id);

        $stmt = $this->conn->prepare($query);

        //Bind all params
        if (count($bindings) > 0) {
            foreach ($bindings as $key => $value) {
                $stmt->bindValue($key + 1, $value);
            }
        }

        try {
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                return json_encode(
                    array(
                        'id' => $this->id,
                        'quote' => $this->quote,
                        'author_id' => $this->author_id,
                        'category_id' => $this->category_id
                    )
                );
            } else {
                return json_encode(
                    array('message' => 'No Quotes Found')
                );
            }
        } catch (Exception $e) {
            //Check for foreign key failure state
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
            if ($errorCode === '23503') { //23503 is the state error for foreign key violations
                //stripos() will return false if passed string does not contain indicated phrase
                if (stripos($errorMessage, 'author_id') !== false) {
                    return json_encode(
                        array('message' => 'author_id Not Found')
                    );
                } elseif (stripos($errorMessage, 'category_id') !== false) {
                    return json_encode(
                        array('message' => 'category_id Not Found')
                    );
                } else {
                    return json_encode(
                        array('message' => 'unknown foreign key error occurred')
                    );
                }
            } else {
                return $errorMessage;
            }
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
            if ($stmt->rowCount() > 0) {
                return json_encode(
                    array('id' => "$this->id")
                );
            } else {
                return json_encode(
                    array('message' => 'No Quotes Found')
                );
            }
        } else {
            return json_encode(
                array('message' => 'Query Failed')
            );
        }
    }
}
