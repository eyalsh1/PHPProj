<?php

include 'api.php';
include 'Header.php';

//echo Course::printAll();
//echo Student::printAll();
//print_r($_SESSION);
//print_r($courses);
//print_r($students);

$courses = Course::read();
$students = Student::read();

$html = buildAside($courses, $students);

if (!isset($_GET['action']))
{
    $html .= buildSummary($courses, $students);
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
                case 'course':
                    $html .= "<div class=\"col-sm-8\">";
                    //$html .= file_get_contents('../templates/AddEditCourse.html');
                    $html .= AddCourse();
                    $html .= "</div>";
                    break;

                case 'student':
                    $html .= "<div class=\"col-sm-8\">";
                    //$html .= file_get_contents('../templates/AddEditStudent.html');
                    $html .= AddStudent($courses);
                    $html .= "</div>";
                    break;
            }
            break;

        case 'edit':
            switch ($_GET['type']) {
                case 'course':
                    $id = $_GET['id'];
                    $html .= "<div class=\"col-sm-8\">";
                    //$html .= file_get_contents('../templates/AddEditCourse.html');
                    $html .= EditCourse($id, $courses);
                    $html .= "</div>";
                    break;

                case 'student':
                    $id = $_GET['id'];
                    $html .= "<div class=\"col-sm-8\">";
                    //$html .= file_get_contents('../templates/AddEditStudent.html');
                    $html .= EditStudent($id, $courses, $students);
                    $html .= "</div>";
                    break;
            }
            break;

        case 'show':
            switch ($_GET['type']) {
                case 'course':
                    $id = $_GET['id'];
                    $html .= "<div class=\"col-sm-8\">";
                    //$html .= file_get_contents('../templates/ShowCourse.html');
                    $html .= ShowCourse($id, $courses, $students);
                    $html .= "</div>";
                    break;

                case 'student':
                    $id = $_GET['id'];
                    $html .= "<div class=\"col-sm-8\">";
                    //$html .= file_get_contents('../templates/ShowStudent.html');
                    $html .= ShowStudent($id, $students);
                    $html .= "</div>";
                    break;
            }
            break;

        default:

            break;
    }
}

$html .= "</div>";
echo $html;

function buildAside($courses, $students)
{
    $html = "<div class=\"row\">
                <div class=\"col-sm-4\">
                    <div class=\"row\">
                        <div class=\"col-sm-5\"><h4>Courses</h4></div>
                        <div class=\"col-sm-1\"><a href=\"?action=insert&type=course\"><button type=\"button\" class=\"btn\">+</button></a></div>
                        <div class=\"col-sm-5\"><h4>Students</h4></div>
                        <div class=\"col-sm-1\"><a href=\"?action=insert&type=student\"><button type=\"button\" class=\"btn\">+</button></a></div>";

    $maxRows = max(count($courses), count($students));
    for ($i = 0; $i < $maxRows; $i++) {
        $html .= "<div class=\"col-sm-6\">" . buildCourseLink($courses, $i) . "</div>
              <div class=\"col-sm-6\">" . buildStudentLink($students, $i) . "</div>";
    }
    $html .= "</div></div>";
    return $html;
}

function buildSummary($courses, $students)
{
    $html = "<div class=\"col-sm-8\">
                <div class=\"row\">
                    <div class=\"col-sm-12\"><h1>School summary</h1></div>
                    <div class=\"col-sm-12\"><h2>Courses amount is " . count($courses) . "</h2></div>
                    <div class=\"col-sm-12\"><h2>Students amount is " . count($students) . "</h2></div>
                </div>
             </div>";
    return $html;
}

function buildCourseLink($courses, $i)
{
    $link = "";
    if ($i < count($courses)) {
        $link .= "<a href=\"?action=show&type=course&id={$courses[$i]['id']}\">";
        $link .= "<figure><img src=\"../img/courses/{$courses[$i]['image']}\" width=100% height=30%>";
        $link .= "<figcaption style=color:blue;>{$courses[$i]['name']}</figcaption>";
        $link .= "<figcaption style=color:blue;>{$courses[$i]['description']}</figcaption></a></figure><br>";
    }
    return $link;
}

function buildStudentLink($students, $i)
{
    $link = "";
    if ($i < count($students)) {
        $link .= "<a href=\"?action=show&type=student&id={$students[$i]['id']}\">";
        $link .= "<figure><img src=\"../img/students/{$students[$i]['image']}\" width=100% height=30%>";
        $link .= "<figcaption style=color:blue;>{$students[$i]["name"]}</figcaption>";
        $link .= "<figcaption style=color:blue;>{$students[$i]["phone"]}</figcaption></a></figure><br>";
    }
    return $link;
}

function EditCourse($id, $courses)
{
    $html = LoadBootstrap();
    $html .= "<form action=\"School.php\">
                <div class=\"col-sm-12\">
                    <h2>Add / Edit Course</h2>
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
                    <input type=\"text\" class=\"form-control\" name=\"name\"  value=\"{$courses[$id-1]['name']}\">
                </div>
            
                <label class=\"control-label col-sm-2\">Description:</label>
                <div class=\"col-sm-10\">
                    <textarea class=\"form-control\" rows=\"5\" name=\"description\">{$courses[$id-1]['description']}</textarea>
                </div>
            
                <div class=\"col-sm-12\"><br></div>
            
                <label class=\"control-label col-sm-2\">Image:</label>
                <div class=\"col-sm-2\">
                    <img src=\"../img/Courses/{$courses[$id-1]['image']}\" alt=\"course_img\" width=100%>
                </div>
                <div class=\"col-sm-8\">
                    <input type=\"file\" name=\"img\" accept=\"img/*\">
                </div>
            
                <h2 class=\"control-label col-sm-12\"><br>Total " . Student::countCourseStudents($id) . " student";

    if (Student::countCourseStudents($id) != 1)
        $html .= "s"; // Add s for plural

    $html .= " taking this course</h2>
            </form>
        </body>
        </html>";
    return $html;
}

function AddCourse()
{
    $html = LoadBootstrap();
    $html .= "<form action=\"School.php\">
                <div class=\"col-sm-12\">
                    <h2>Add / Edit Course</h2>
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
            
                <label class=\"control-label col-sm-2\">Description:</label>
                <div class=\"col-sm-10\">
                    <textarea class=\"form-control\" rows=\"5\" name=\"description\"></textarea>
                </div>
            
                <div class=\"col-sm-12\"><br></div>
            
                <label class=\"control-label col-sm-2\">Image:</label>
                <div class=\"col-sm-2\">
                    <img src=\"../img/school_img.png\" alt=\"course_img\" width=100%>
                </div>
                <div class=\"col-sm-8\">
                    <input type=\"file\" name=\"img\" accept=\"img/*\">
                </div>
            </form>
        </body>
        </html>";
    return $html;
}

function EditStudent($id, $courses, $students)
{
    $html = LoadBootstrap();
    $html .= "<form action=\"School.php\">
                <div class=\"col-sm-12\">
                    <h2>Add / Edit Student</h2>
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
                    <input type=\"text\" class=\"form-control\" name=\"name\" value=\"{$students[$id-1]['name']}\">
                </div>
            
                <label class=\"control-label col-sm-2\">Phone:</label>
                <div class=\"col-sm-10\">
                    <input type=\"text\" class=\"form-control\" name=\"phone\" value=\"{$students[$id-1]['phone']}\">
                </div>
            
                <label class=\"control-label col-sm-2\">Email:</label>
                <div class=\"col-sm-10\">
                    <input type=\"email\" class=\"form-control\" name=\"email\" value=\"{$students[$id-1]['email']}\">
                </div>
            
                <label class=\"control-label col-sm-2\">Image:</label>
                <div class=\"col-sm-2\">
                    <img src=\"../img/Students/{$students[$id-1]['image']}\" alt=\"student_img\" width=100%>
                </div>
                <div class=\"col-sm-8\">
                    <input type=\"file\" name=\"img\" accept=\"img/*\">
                </div>
            
                <div class=\"col-sm-12\"><br></div>
            
                <label class=\"col-sm-2\">Courses:</label>
                <div class=\"col-sm-10\">";
    $html .= printStudentCourses($id, $courses);
    $html .= "</div>  
            </form>
        </body>
        </html>";
    return $html;
}

function AddStudent($courses)
{
    $html = LoadBootstrap();
    $html .= "<form action=\"School.php\">
                <div class=\"col-sm-12\">
                    <h2>Add / Edit Student</h2>
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
            
                <label class=\"control-label col-sm-2\">Image:</label>
                <div class=\"col-sm-2\">
                    <img src=\"../img/school_img.png\" alt=\"student_img\" width=100%>
                </div>
                <div class=\"col-sm-8\">
                    <input type=\"file\" name=\"img\" accept=\"img/*\">
                </div>
            
                <div class=\"col-sm-12\"><br></div>
            
                <label class=\"col-sm-2\">Courses:</label>
                <div class=\"col-sm-10\">";
    $html .= printStudentCourses(0, $courses);
    $html .= "</div>  
            </form>
        </body>
        </html>";
    return $html;
}

function printStudentCourses($id, $courses)
{
    $html = "";
    $rows = count($courses);
    for ($i = 0; $i < $rows; $i++) {
        $html .= "<label class=\"radio-inline\">
                    <input type=\"radio\" name=\"optradio\" value=\"{$courses[$i]['id']}\"";
        if ($courses[$i]['id'] === $id)
            $html .= " checked";
        $html .= ">{$courses[$i]['name']}</label>";
    }
    return $html;
}

function ShowCourse($id, $courses, $students)
{
    $html = LoadBootstrap();
    $html .= "<form action=\"School.php\">
                <div class=\"col-sm-10\">
                    <h2>{$courses[$id-1]['name']}</h2>
                </div>
                <div class=\"col-sm-2\">
                    <button type=\"submit\" class=\"btn btn-default\">Edit</button>
                </div>
        
                <div class=\"col-sm-12\"><hr></div>
        
                <div class=\"col-sm-4\">
                    <img src=\"../img/Courses/{$courses[$id - 1]['image']}\" alt=\"course_img\" width=100%>
                    
                </div>
                <div class=\"col-sm-8\">
                    <h1>{$courses[$id-1]['name']}, " . Student::countCourseStudents($id) . " Student";

    if (Student::countCourseStudents($id) != 1)
        $html .= "s"; // Add s for plural

    $html .= "</h1>
                    <span>{$courses[$id-1]['description']}</span>
                </div>
        
                <div class=\"col-sm-12\">
                    <h2>Students</h2>
                </div>";
    $html .= printCourseStudents($id, $students);

    $html .= "<input type=\"hidden\" name=\"action\" value=\"edit\">
              <input type=\"hidden\" name=\"type\" value=\"course\">
              <input type=\"hidden\" name=\"id\" value=\"{$id}\"> 
            </form>
        </body>
        </html>";
    return $html;
}

function printCourseStudents($id, $students)
{
    $html = "";
    $rows = count($students);
    for ($i = 0; $i < $rows; $i++) {
        if ($students[$i]['course_id'] === $id)
            $html .= "<label class=\"control-label col-sm-12\">{$students[$i]['name']}</label>";
    }
    return $html;
}

function ShowStudent($id, $students)
{
    $html = LoadBootstrap();
    $html .= "<form action=\"School.php\">
                <div class=\"col-sm-1\">
                    <h2>Student</h2>
                </div>
                <div class=\"col-sm-11\">
                    <button type=\"submit\" class=\"btn btn-default\">Edit</button>
                </div>
        
                <div class=\"col-sm-12\"><hr></div>
        
                <div class=\"col-sm-6\">
                    <img src=\"../img/Students/{$students[$id-1]['image']}\" alt=\"student_img\" width=100%>
                </div>
                <div class=\"col-sm-6\">
                    <h3>{$students[$id-1]['name']}</h3>
                    <h3>{$students[$id-1]['phone']}</h3>
                    <h3>{$students[$id-1]['email']}</h3>
                    <h3>Course: {$students[$id-1]['course']}</h3>
                </div>
                
                <input type=\"hidden\" name=\"action\" value=\"edit\">
                <input type=\"hidden\" name=\"type\" value=\"student\">
                <input type=\"hidden\" name=\"id\" value=\"{$id}\">   
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