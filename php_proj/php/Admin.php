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
            $html .= "<div class=\"col-sm-8\">";
            //$html .= file_get_contents('../templates/AddEditAdmin.html');
            $html .= AddAdmin();
            $html .= "</div>";
            break;

        case 'edit':
            $id = $_GET['id'];
            $html .= "<div class=\"col-sm-8\">";
            //$html .= file_get_contents('../templates/AddEditAdmin.html');
            $html .= EditAdmin($id, $admins);
            $html .= "</div>";
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
                        <div class=\"col-sm-2\"><a href=\"?action=insert\"><button type=\"button\" class=\"btn\">+</button></a></div>";

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
                    <div class=\"col-sm-12\"><h1>Admin summary</h1></div>
                    <div class=\"col-sm-12\"><h2>Admin amount is " . count($admins) . "</h2></div>
                </div>
             </div>";
    return $html;
}

function buildAdminLink($admins, $i)
{
    $link = "";
    $link .= "<a href=\"?action=edit&id={$admins[$i]['id']}\">";
    $link .= "<figure><img src=\"../img/admins/{$admins[$i]['image']}\" width=100% height=30%>";
    $link .= "<figcaption style=color:blue;>{$admins[$i]["name"]}, {$admins[$i]["role"]}</figcaption>";
    $link .= "<figcaption style=color:blue;>{$admins[$i]["phone"]}</figcaption>";
    $link .= "<figcaption style=color:blue;>{$admins[$i]["email"]}</figcaption></a></figure><br>";
    return $link;
}

function AddAdmin()
{
    $html = LoadBootstrap();
    $html .= "<form action=\"Admin.php\">
                <div class=\"col-sm-12\">
                    <h2>Add / Edit Admin</h2>
                    <hr>
                </div>
            
                <div class=\"col-sm-2\">
                    <button type=\"submit\" name=\"save-btn\" class=\"btn btn-default\" style=\"width:100%;\">Save</button>
                </div>
                <div class=\"col-sm-10\"></div>
            
                <div class=\"col-sm-12\"><br></div>
            
                <label class=\"control-label col-sm-2\">Name:</label>
                <div class=\"col-sm-10\">
                    <input type=\"text\" class=\"form-control\" name=\"name\">
                </div>
            
                <label class=\"control-label col-sm-2\">Phone:</label>
                <div class=\"col-sm-10\">
                    <input type=\"text\" class=\"form-control\" name=\"phone\">
                </div>
            
                <label class=\"control-label col-sm-2\">Email:</label>
                <div class=\"col-sm-10\">
                    <input type=\"email\" class=\"form-control\" name=\"email\">
                </div>
            
                <label class=\"control-label col-sm-2\">Role:</label>
                <div class=\"col-sm-10\">
                    <input type=\"text\" class=\"form-control\" name=\"role\">
                </div>
            
                <div class=\"col-sm-12\"><br></div>
            
                <label class=\"control-label col-sm-2\">Image:</label>
                <div class=\"col-sm-2\">
                    <img src=\"../img/school_img.png\" alt=\"school_img\" width=100%>
                </div>
                <div class=\"col-sm-8\">
                    <input type=\"file\" name=\"img\" accept=\"image/*\">
                </div> 
            </form>
        </body>
        </html>";
    return $html;
}


function EditAdmin($id, $admins)
{
    $html = LoadBootstrap();
    $html .= "<form action=\"Admin.php\" onSubmit=\"return confirm('Are you sure you want to delete?')\">
                <div class=\"col-sm-12\">
                    <h2>Add / Edit Admin</h2>
                    <hr>
                </div>
            
                <div class=\"col-sm-2\">
                    <button type=\"submit\" name=\"save-btn\" class=\"btn btn-default\" style=\"width:100%;\">Save</button>
                </div>
                <div class=\"col-sm-10\">
                    <button type=\"submit\" name=\"delete-btn\" class=\"btn btn-default\">Delete</button>
                </div>
            
                <div class=\"col-sm-12\"><br></div>
            
                <label class=\"control-label col-sm-2\">Name:</label>
                <div class=\"col-sm-10\">
                    <input type=\"text\" class=\"form-control\" name=\"name\" value=\"{$admins[$id - 1]['name']}\">
                </div>
            
                <label class=\"control-label col-sm-2\">Phone:</label>
                <div class=\"col-sm-10\">
                    <input type=\"text\" class=\"form-control\" name=\"phone\" value=\"{$admins[$id - 1]['phone']}\">
                </div>
            
                <label class=\"control-label col-sm-2\">Email:</label>
                <div class=\"col-sm-10\">
                    <input type=\"email\" class=\"form-control\" name=\"email\" value=\"{$admins[$id - 1]['email']}\">
                </div>
            
                <label class=\"control-label col-sm-2\">Role:</label>
                <div class=\"col-sm-10\">
                    <input type=\"text\" class=\"form-control\" name=\"role\" value=\"{$admins[$id - 1]['role']}\">
                </div>
            
                <div class=\"col-sm-12\"><br></div>
            
                <label class=\"control-label col-sm-2\">Image:</label>
                <div class=\"col-sm-2\">
                    <img src=\"../img/Admins/{$admins[$id - 1]['image']}\" alt=\"admin_img\" width=100%>
                </div>
                <div class=\"col-sm-8\">
                    <input type=\"file\" name=\"img\" accept=\"image/*\">
                </div> 
            </form>
        </body>
        </html>";
    return $html;
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