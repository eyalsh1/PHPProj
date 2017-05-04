<?php
include 'Person.php';
include 'DB.php';

class Course implements ISavable
{
    //use SelectAll;
    public $id;
    public $name;
    public $description;
    //public $img;
    //private static $table_name = 'courses';

    function __construct($id, $name, $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        //$this->img = $img;
    }

    public function insert()
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $stmt = $conn->prepare("INSERT INTO courses (name, description) VALUES (?, ?)");
        $stmt->bind_param('ss', $this->name, $this->description);
        $stmt->execute();

        if ($stmt->error)
            echo $stmt->error;
        else
            echo "Insert new Course: ". $this->name ." success";
    }

    public function delete($id)
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $result = $conn->query("DELETE FROM courses WHERE id = '$id'");

        if ($result)
            echo "delete course success";
        else
            echo "delete course failed";
    }

    public function read()
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $result = $conn->query("SELECT * FROM courses");

        $rows = array();

        if ($result->num_rows > 0)
        {
            while ($row = $result->fetch_assoc())
                $rows[] = $row;
            echo json_encode($rows);
        }
        else
            echo "0 results";
    }

    public function count() {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $result = $conn->query("SELECT * FROM courses" );

        if ($result->num_rows > 0)
        {
            $count = $result->num_rows;
            echo json_encode($count);
        }
        else
            echo "0";
    }
}