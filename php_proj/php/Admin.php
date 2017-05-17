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

        case 'insert': // Shows Add Admin form
            $html .= "<div class=\"col-sm-8\">";
            //$html .= file_get_contents('../templates/AddEditAdmin.html');
            $html .= AddAdmin();
            $html .= "</div>";
            break;

        case 'edit': // Shows Edit Admin form
            $html .= "<div class=\"col-sm-8\">";
            //$html .= file_get_contents('../templates/AddEditAdmin.html');
            $html .= EditAdmin($_GET['id']);
            $html .= "</div>";
            break;

        case 'save': // Update Admin
            Admin::update($_GET['id'], $_GET['name'], $_GET['phone'], $_GET['email'], $_GET['img'], Admin::GetRoleId($_GET['role'])['id'], $_GET['password']);
            header("Location: Admin.php");
            break;

        case 'add': // Add Admin
            $admin = new Admin('', $_GET['name'], $_GET['phone'], $_GET['email'], $_GET['img'], Admin::GetRoleId($_GET['role'])['id'], $_GET['password']);
            $admin->insert();
            header("Location: Admin.php");
            break;

        case 'delete':
            Admin::delete($_GET['id']);
            header("Location: Admin.php");
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
                    <h2>Add Admin</h2>
                    <hr>
                </div>
            
                <div class=\"col-sm-2\">
                    <button type=\"submit\" name=\"action\" value=\"add\" class=\"btn btn-default\" style=\"width:100%;\">Save</button>
                </div>
                <div class=\"col-sm-10\"></div>
            
                <div class=\"col-sm-12\"><br></div>
            
                <label class=\"control-label col-sm-2\">Name:</label>
                <div class=\"col-sm-10\">
                    <input type=\"text\" class=\"form-control\" name=\"name\" required>
                </div>
            
                <label class=\"control-label col-sm-2\">Phone:</label>
                <div class=\"col-sm-10\">
                    <input type=\"tel\" class=\"form-control\" name=\"phone\" required>
                </div>
            
                <label class=\"control-label col-sm-2\">Email:</label>
                <div class=\"col-sm-10\">
                    <input type=\"email\" class=\"form-control\" name=\"email\" required>
                </div>
            
                <label class=\"control-label col-sm-2\">Password:</label>
                <div class=\"col-sm-10\">
                    <input type=\"password\" class=\"form-control\" name=\"password\" required>
                </div>
            
                <label class=\"control-label col-sm-2\">Role:</label>
                <div class=\"col-sm-10\">";
                    //<input type=\"text\" class=\"form-control\" name=\"role\">
    $html .= AddRolesSelect('');
    $html .= "</div>
            
                <div class=\"col-sm-12\"><br></div>
            
                <label class=\"control-label col-sm-2\">Image:</label>
                <div class=\"col-sm-2\">
                    <img src=\"../img/school_img.png\" alt=\"school_img\" width=100%>
                </div>
                <div class=\"col-sm-8\">
                    <input type=\"file\" name=\"img\" accept=\"image/*\" required>
                </div> 
            </form>
        </body>
        </html>";
    return $html;
}

function EditAdmin($id)
{
    $admin = Admin::get($id);
    $html = LoadBootstrap();
    $html .= "<form action=\"Admin.php\" onSubmit=\"return confirm('Are you sure you want to delete?')\">
                <div class=\"col-sm-12\">
                    <h2>Edit Admin</h2>
                    <hr>
                </div>
            
                <div class=\"col-sm-2\">
                    <button type=\"submit\" name=\"action\" value=\"save\" class=\"btn btn-default\" style=\"width:100%;\">Save</button>
                </div>
                <div class=\"col-sm-10\">
                    <button type=\"submit\" name=\"action\" value=\"delete\" class=\"btn btn-default\">Delete</button>
                </div>
            
                <input type=\"hidden\" name=\"id\" value=\"{$id}\">
                
                <div class=\"col-sm-12\"><br></div>
            
                <label class=\"control-label col-sm-2\">Name:</label>
                <div class=\"col-sm-10\">
                    <input type=\"text\" class=\"form-control\" name=\"name\" value=\"{$admin['name']}\" required>
                </div>
            
                <label class=\"control-label col-sm-2\">Phone:</label>
                <div class=\"col-sm-10\">
                    <input type=\"tel\" class=\"form-control\" name=\"phone\" value=\"{$admin['phone']}\" required>
                </div>
            
                <label class=\"control-label col-sm-2\">Email:</label>
                <div class=\"col-sm-10\">
                    <input type=\"email\" class=\"form-control\" name=\"email\" value=\"{$admin['email']}\" required>
                </div>
                
                <label class=\"control-label col-sm-2\">Password:</label>
                <div class=\"col-sm-10\">
                    <input type=\"password\" class=\"form-control\" name=\"password\" value=\"{$admin['password']}\" required>
                </div>
                
                <label class=\"control-label col-sm-2\">Role:</label>
                <div class=\"col-sm-10\">";
                    //<input type=\"text\" class=\"form-control\" name=\"role\" value=\"{$admin['role']}\">
    $html .= AddRolesSelect($admin['role']);
    $html .= "</div>
           
                <div class=\"col-sm-12\"><br></div>
            
                <label class=\"control-label col-sm-2\">Image:</label>
                <div class=\"col-sm-2\">
                    <img src=\"../img/Admins/{$admin['image']}\" alt=\"admin_img\" width=100%>
                </div>
                <div class=\"col-sm-8\">
                    <input type=\"file\" name=\"img\" accept=\"image/*\">
                </div> 
                
                
            </form>
        </body>
        </html>";
    return $html;
}

function AddRolesSelect($role)
{
    $html = "<select class=\"form-control\" name=\"role\" required><option></option>";

    $roles = Admin::GetRoles();
    $rows = count($roles);
    for ($i = 0; $i < $rows; $i++) {
        $html .= "<option";
        if ($role === $roles[$i]['name'])
            $html .= " selected=\"selected\"";
        $html .= ">{$roles[$i]['name']}</option>";
    }
    $html .= "</select>";
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