<?php

class User
{

    // database connection and table name
    private $conn;
    private $table_name = "users";

    // object properties
    public $id;
    public $name;
    public $email;
    public $country_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // create user
    function create()
    {

        //write query
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    name=:name, email=:email, country_id=:country_id";

        $stmt = $this->conn->prepare($query);

        // posted values
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->country_id = htmlspecialchars(strip_tags($this->country_id));


        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":country_id", $this->country_id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }

    }

    function readAll($from_record_num, $records_per_page)
    {

        $query = "SELECT
                id, name, email, country_id
            FROM
                " . $this->table_name . "
            ORDER BY
                name ASC
            LIMIT
                {$from_record_num}, {$records_per_page}";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // used for paging user
    public function countAll()
    {

        $query = "SELECT id FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $num = $stmt->rowCount();

        return $num;
    }

    function readOne()
    {

        $query = "SELECT
                name, email, country_id
            FROM
                " . $this->table_name . "
            WHERE
                id = ?
            LIMIT
                0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->name = $row['name'];
        $this->email = $row['email'];
        $this->country_id = $row['country_id'];
    }

    function update()
    {

        $query = "UPDATE
                " . $this->table_name . "
            SET
                name = :name,
                email = :email,
                country_id = :country_id
            WHERE
                id = :id";

        $stmt = $this->conn->prepare($query);

        // posted values
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->country_id = htmlspecialchars(strip_tags($this->country_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind parameters
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':country_id', $this->country_id);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;

    }

    // delete the user
    function delete()
    {

        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if ($result = $stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

?>