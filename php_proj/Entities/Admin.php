<?php
//include 'Person.php';
//include 'DB.php';

class Admin extends Person {
    public $image;
    public $role_id;
    public $password;
	/*public $role_name;
	use SelectAll;
	private static $table_name = 'admins';

	function __construct($id, $name, $img, $password = null, $role_id = null, $role_name = null) {
		parent::__construct($id, $name, $img);
		$this->password = $password;
		$this->role_id = $role_id;
		$this->role_name = $role_name;
	}*/

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
        $stmt->bind_param('ssssis', $this->name, $this->phone, $this->email, $this->image, $this->role_id, $this->password);
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

        if($result)
            echo "delete admin success";
        else
            echo "delete admin failed";
    }

    public function read()
    {
        $conn = DB::getInstance()->getConnection();
        if ($conn->errno) {echo $conn->error; die();}

        $result = $conn->query("SELECT admins.id as id, admins.name as name, admins.phone as phone, admins.email as email, admins.image as image, roles.name as role FROM admins INNER JOIN roles on roles.id = admins.role_id");

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

     public function printAll()
    {
        $html = "<h3>Admins</h3>";
        $rows = self::read();
        for ($i=0, $count = count($rows); $i < $count; $i++)
        {
            $html .= "<div class=\"row\">";
            $html .= "<div class=\"col-md-1\">";
            $html .= "<a href=\"?page=admins&admin_id={$rows[$i]['id']}\">";
            $html .= "<figure><img src=\"../img/admins/{$rows[$i]['image']}\" width=100%>";
            $html .= "<figcaption style=color:blue;>{$rows[$i]["name"]}, {$rows[$i]["role"]}</figcaption>";
            $html .= "<figcaption style=color:blue;>{$rows[$i]["phone"]}</figcaption>";
            $html .= "<figcaption style=color:blue;>{$rows[$i]["email"]}</figcaption></a></figure><br>";
            $html .= "</div></div>";
        }
        return $html;
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
        session_destroy();
        echo "logout";
    }
}