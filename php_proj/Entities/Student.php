<?php


class Student extends Person {
    public $image;
    public $course_id;

    function __construct($id, $name, $phone, $email, $image, $course_id)
    {
        parent::__construct($id, $name, $phone, $email);
        $this->image = $image;
        $this->course_id = $course_id;
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

    public function countCourseStudents($id)
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $result = $conn->query("SELECT COUNT(*) as total FROM students WHERE course_id=$id");

        return $result->fetch_assoc()['total'];
    }

    public function insert()
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $stmt = $conn->prepare("INSERT INTO students (name, phone, email, image, course_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssi', $this->name, $this->phone, $this->email, $this->image, $this->course_id);
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

        $result = $conn->query("DELETE FROM students WHERE id = $id");

        if ($result)
            echo "delete student success";
        else
            echo "delete student failed";
    }

    public function read()
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $result = $conn->query("SELECT students.id as id, students.name as name, students.phone as phone, students.email as email, students.image as image, students.course_id as course_id, courses.name as course FROM students INNER JOIN courses on students.course_id = courses.id");

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

    function get($id)
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $result = $conn->query("SELECT students.name as name, students.phone as phone, students.email as email, students.image as image, courses.name as course, students.course_id as course_id FROM students INNER JOIN courses on students.course_id = courses.id WHERE students.id=$id");

        return $result->fetch_assoc();
    }

    public function update($id, $name, $phone, $email, $image, $course_id)
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        if ($image != '') {
            $stmt = $conn->prepare("UPDATE students SET name=?, phone=?, email=?, image=?, course_id=? WHERE id=?");
            $stmt->bind_param('ssssii', $name, $phone, $email, $image, $course_id, $id);
        }
        else {
            $stmt = $conn->prepare("UPDATE students SET name=?, phone=?, email=?, course_id=? WHERE id=?");
            $stmt->bind_param('sssii', $name, $phone, $email, $course_id, $id);
        }
        $stmt->execute();

        if ($stmt->error)
            echo $stmt->error;
        else
            echo "Student: ". $name ." was successfully updated";
    }
}