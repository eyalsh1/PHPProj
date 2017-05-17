<?php

class Course implements ISavable
{
    public $id;
    public $name;
    public $description;
    public $image;

    function __construct($id, $name, $description, $image)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->image = $image;
    }

    public function count()
    {
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

    public function insert()
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $stmt = $conn->prepare("INSERT INTO courses (name, description, image) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $this->name, $this->description, $this->image);
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

        $result = $conn->query("DELETE FROM courses WHERE id = $id");

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
            //echo json_encode($rows);
        }
        else
            echo "0 results";
        return $rows;
    }

    /*public function GetName($id)
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $result = $conn->query("SELECT name FROM courses WHERE id='$id'");

        return $result->fetch_assoc()['name'];
    }*/

    public function get($id)
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $result = $conn->query("SELECT name, description, image FROM courses WHERE id=$id");

        return $result->fetch_assoc();
    }

    public function update($id, $name, $description, $image)
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        if ($image != '') {
            $stmt = $conn->prepare("UPDATE courses SET name=?, description=?, image=? WHERE id=?");
            $stmt->bind_param('sssi', $name, $description, $image, $id);
        }
        else {
            $stmt = $conn->prepare("UPDATE courses SET name=?, description=? WHERE id=?");
            $stmt->bind_param('ssi', $name, $description, $id);
        }
        $stmt->execute();

        if ($stmt->error)
            echo $stmt->error;
        else
            echo "Course: ". $name ." was successfully updated";
    }
}