<?php

include 'DB.php';

function login_check($uname, $pwd)
{
    $conn = DB::getInstance()->getConnection();
    if ($conn->errno) {
        echo $conn->error;
        die();
    }

    $stmt = $conn->prepare("SELECT role_id, image, password FROM admins WHERE email = ?");
    $stmt->bind_param('s',$uname);

    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($role_id, $image, $ret_pwd);

    if($stmt->num_rows()) {
        while ($stmt->fetch())
        {
            if (password_verify($pwd, $ret_pwd)) {
                session_start();
                $_SESSION['username'] = $uname;
                $_SESSION['image'] = $image;
                $_SESSION['role_id'] = $role_id;
                $session_data = [$uname, $image, $role_id];
                echo json_encode($session_data);
                return true;
            }
        }

    }

    echo "<br>Wrong Username or Password";
    return false;
}
