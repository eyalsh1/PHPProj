<?php

class Admin extends Person {
    public $image;
    public $role_id;
    public $password;

    function __construct($id, $name, $phone, $email, $image, $role_id, $password)
    {
        parent::__construct($id, $name, $phone, $email);
        $this->image = $image;
        $this->role_id = $role_id;
        $this->password = $password;
    }

    public function count()
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $result = $conn->query("SELECT * FROM admins");

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

        $stmt = $conn->prepare("INSERT INTO admins (name, phone, email, image, role_id, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssis', $this->name, $this->phone, $this->email, $this->image, $this->role_id, password_hash($this->password, PASSWORD_DEFAULT));
        $stmt->execute();

        if ($stmt->error)
            echo $stmt->error;
        else
            echo "Insert new Admin: ". $this->name ." success";
    }

    public function delete($id)
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $result = $conn->query("DELETE FROM admins WHERE id = '$id'");

        if ($result)
            echo "delete admin success";
        else
            echo "delete admin failed";
    }

    public function read()
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $result = $conn->query("SELECT admins.id as id, admins.name as name, admins.phone as phone, admins.email as email, admins.image as image, admins.role_id as role_id, roles.name as role FROM admins INNER JOIN roles on roles.id = admins.role_id");

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

    public function get($id)
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $result = $conn->query("SELECT admins.name as name, admins.phone as phone, admins.email as email, admins.image as image, admins.password as password, admins.role_id as role_id, roles.name as role FROM admins INNER JOIN roles on roles.id = admins.role_id WHERE admins.id=$id");

        return $result->fetch_assoc();
    }

    public function GetRoles()
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $result = $conn->query("SELECT * FROM roles");

        $roles = array();

        if ($result->num_rows > 0)
        {
            while ($role = $result->fetch_assoc())
                $roles[] = $role;
            //echo json_encode($roles);
        }
        else
            echo "0 results";
        return $roles;
    }

    public function GetRoleId($role)
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $result = $conn->query("SELECT id FROM roles WHERE name='$role'");

        return $result->fetch_assoc();
    }

    public function update($id, $name, $phone, $email, $image, $role_id, $password)
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        if ($image != '') {
            $stmt = $conn->prepare("UPDATE admins SET name=?, phone=?, email=?, image=?, role_id=?, password=? WHERE id=?");
            $stmt->bind_param('ssssisi', $name, $phone, $email, $image, $role_id, password_hash($password, PASSWORD_DEFAULT), $id);
        }
        else {
            $stmt = $conn->prepare("UPDATE admins SET name=?, phone=?, email=?, role_id=?, password=? WHERE id=?");
            $stmt->bind_param('sssisi', $name, $phone, $email, $role_id, password_hash($password, PASSWORD_DEFAULT), $id);
        }
        $stmt->execute();

        if ($stmt->error)
            echo $stmt->error;
        else
            echo "Admin: ". $name ." was successfully updated";
    }

    public function login($username, $password)
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $stmt = $conn->prepare("SELECT role_id, image FROM admins WHERE name = ? AND password = ? ");
        $stmt->bind_param('ss',$username, $password);

        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($role_id, $image);

        if ($stmt->num_rows())
        {
            while ($stmt->fetch()) {
                $_SESSION['username'] = $username;
                $_SESSION['image'] = $image;
                $_SESSION['role_id'] = $role_id;
                $session_data = [$username, $image, $role_id];
            }
            echo json_encode($session_data);
        }
        else
            echo "Wrong Username or Password";
    }

    public function logout()
    {
        //session_unset(); // remove all session variables
        session_destroy(); // destroy the session
        echo "logout";
    }
}