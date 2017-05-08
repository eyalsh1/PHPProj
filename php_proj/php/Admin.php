<?php

include 'api.php';
include 'Header.php';

//echo Admin::printAll();
//echo $_SESSION['role_id'];
//print_r($_SESSION);

$admins = Admin::read();

$html = buildAside($admins);

if (!isset($_GET['action']))
{
    $html .= buildSummary($admins);
}
else
{
    switch ($_GET['action']) {
        case 'logout':
            Admin::logout();
            header("Location: ../index.html");
            break;

        case 'insert':
            switch ($_GET['type']) {
                case 'admin':

                    break;
            }
            break;

        case 'edit':
            switch ($_GET['type']) {
                case 'admin':
                    $id = $_GET['id'];

                    break;
            }
            break;

        default:
            break;
    }
}

$html .= "</div>";
echo $html;

function buildAside($admins)
{
    $html = "<div class=\"row\">
                <div class=\"col-sm-2\">
                    <div class=\"row\">
                        <div class=\"col-sm-10\"><h4>Administrators</h4></div>
                        <div class=\"col-sm-2\"><a href=\"?action=insert&type=admin\"><button type=\"button\" class=\"btn\">+</button></a></div>";

    $rows = count($admins);
    for ($i = 0; $i < $rows; $i++)
    {
        if ($_SESSION['role_id'] <= $admins[$i]['role_id']) // Permissions - can only see same level and lower
            $html .= "<div class=\"col-sm-12\">" . buildAdminLink($admins, $i) . "</div>";
    }
    $html .= "</div></div>";
    return $html;
}

function buildSummary($admins)
{
    $html = "<div class=\"col-sm-10\">
                <div class=\"row\">
                    <div class=\"col-sm-12\" style=\"color:red;\"><h1>Admin summary</h1></div>
                    <div class=\"col-sm-12\" style=\"color:red;\"><h2>Admin amount is " . count($admins) . "</h2></div>
                </div>
             </div>";
    return $html;
}

function buildAdminLink($admins, $i)
{
    $link = "";
    $link .= "<a href=\"?action=edit&type=admin&id={$admins[$i]['id']}\">";
    $link .= "<figure><img src=\"../img/admins/{$admins[$i]['image']}\" width=100% height=30%>";
    $link .= "<figcaption style=color:blue;>{$admins[$i]["name"]}, {$admins[$i]["role"]}</figcaption>";
    $link .= "<figcaption style=color:blue;>{$admins[$i]["phone"]}</figcaption>";
    $link .= "<figcaption style=color:blue;>{$admins[$i]["email"]}</figcaption></a></figure><br>";
    return $link;
}
