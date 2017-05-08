<?php
//include 'Person.php';
//include 'DB.php';

class Student extends Person {
    public $id;
    public $name;
    public $phone;
    public $email;

    /*private static $table_name = 'students';
    public $course_id;

    function __construct($id, $name, $img, $course_id) {
        parent::__construct($id, $name, $img);
        $this->course_id = $course_id;
    }*/

    function __construct($id, $name, $phone, $email)
    {
        parent::__construct($id, $name, $phone, $email);
    }

    public function count()
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $result = $conn->query("SELECT * FROM students");

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

        $stmt = $conn->prepare("INSERT INTO students (name, phone, email) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $this->name, $this->phone, $this->email);
        $stmt->execute();

        if ($stmt->error)
            echo $stmt->error;
        else
            echo "Insert new Student: ". $this->name ." success";
    }

    public function delete($id)
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $result = $conn->query("DELETE FROM students WHERE id = '$id'");

        if ($result)
            echo "delete student success";
        else
            echo "delete student failed";
    }

    public function read()
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $result = $conn->query("SELECT students.id as id, students.name as name, students.phone as phone, students.email as email, students.image as image, courses.name as course FROM students INNER JOIN courses on students.course_id = courses.id");

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

    /*public function printAll()
    {
        $html = "<h3>Students</h3>";
        $rows = self::read();
        for ($i=0, $count = count($rows); $i < $count; $i++)
        {
            $html .= "<a href=\"?page=students&student_id={$rows[$i]['id']}\">";
            $html .= "<figure><img src=\"../img/students/{$rows[$i]['image']}\" width=100%>";
            $html .= "<figcaption style=color:blue;>{$rows[$i]["name"]}</figcaption>";
            $html .= "<figcaption style=color:blue;>{$rows[$i]["phone"]}</figcaption></a></figure><br>";
        }
        return $html;
    }*/
}