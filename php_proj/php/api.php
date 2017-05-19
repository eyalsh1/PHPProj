<?php
session_start();

include '../Entities/DB.php';
include '../Entities/Person.php';
include '../Entities/Admin.php';
include '../Entities/Course.php';
include '../Entities/Student.php';

function login_check($uname, $pwd)
{
    $conn = DB::getInstance()->getConnection();
    if ($conn->errno) {echo $conn->error; die();}

    //$stmt = $conn->prepare("SELECT role_id, image, name, password FROM admins WHERE email = ?");
    $stmt = $conn->prepare("SELECT admins.role_id as role_id, admins.image as image, admins.name as name, admins.password as password, roles.name as role FROM admins INNER JOIN roles on roles.id = admins.role_id WHERE email = ?");
    $stmt->bind_param('s',$uname);

    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($role_id, $image, $name, $ret_pwd, $role);

    if($stmt->num_rows()) {
        while ($stmt->fetch())
        {
            if (password_verify($pwd, $ret_pwd)) {
                $_SESSION['name'] = $name;
                $_SESSION['image'] = $image;
                $_SESSION['role_id'] = $role_id;
                $_SESSION['role'] = $role;
                $session_data = [$name, $image, $role_id, $role];
                //echo json_encode($session_data);
                return true;
            }
        }

    }

    echo "Wrong Username or Password";
    return false;
}

function LoadBootstrap()
{
    $html = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                <link rel="stylesheet" type="text/css" href="../css/index.css">
            </head>
            <body>';
    return $html;
}

function LoadScript()
{
    $html = '<script>
                function changeImg(source, dest) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        source.src = e.target.result;
                    };
                    reader.readAsDataURL(dest.files[0]);
                }
            </script>';
    return $html;
}