<?php

include 'api.php';
include 'Header.php';

//echo Course::printAll();
//echo Student::printAll();
//print_r($_SESSION);
//print_r($_FILES);
//print_r($courses);
//print_r($students);

$courses = Course::read();
$students = Student::read();

$html = buildAside($courses, $students);

if (!isset($_GET['action']) && !isset($_POST['action']))
{
    $html .= buildSummary($courses, $students);
}
else if (isset($_GET['action']))
{
    switch ($_GET['action']) {
        case 'logout':
            Admin::logout();
            header("Location: ../index.html");
            break;

        case 'insert':
        switch ($_GET['type']) {
                case 'course': // Shows Add Course form
                    $html .= "<div class=\"col-sm-8\">";
                    //$html .= file_get_contents('../templates/AddEditCourse.html');
                    $html .= AddCourse();
                    $html .= "</div>";
                    break;

                case 'student': // Shows Add Student form
                    $html .= "<div class=\"col-sm-8\">";
                    //$html .= file_get_contents('../templates/AddEditStudent.html');
                    $html .= AddStudent($courses);
                    $html .= "</div>";
                    break;
            }
            break;

        case 'edit':
            switch ($_GET['type']) {
                case 'course': // Shows Edit Course form
                    $html .= "<div class=\"col-sm-8\">";
                    //$html .= file_get_contents('../templates/AddEditCourse.html');
                    $html .= EditCourse($_GET['id']);
                    $html .= "</div>";
                    break;

                case 'student': // Shows Edit Student form
                    $html .= "<div class=\"col-sm-8\">";
                    //$html .= file_get_contents('../templates/AddEditStudent.html');
                    $html .= EditStudent($_GET['id'], $courses);
                    $html .= "</div>";
                    break;
            }
            break;

        case 'show':
            switch ($_GET['type']) {
                case 'course': // Shows Course
                    $html .= "<div class=\"col-sm-8\">";
                    //$html .= file_get_contents('../templates/ShowCourse.html');
                    $html .= ShowCourse($_GET['id'], $students);
                    $html .= "</div>";
                    break;

                case 'student': // Shows Student
                    $html .= "<div class=\"col-sm-8\">";
                    //$html .= file_get_contents('../templates/ShowStudent.html');
                    $html .= ShowStudent($_GET['id']);
                    $html .= "</div>";
                    break;
            }
            break;

        default:
            echo "action is not defined";
            break;
    }
}
else
{
    switch ($_POST['action']) {
        case 'save':
            switch ($_POST['type']) {
                case 'course': // Update Course
                    moveImg("../img/Courses/");
                    Course::update($_POST['id'], $_POST['name'], $_POST['description'], basename($_FILES['img']['name']));
                    break;

                case 'student': // Update Student
                    moveImg("../img/Students/");
                    Student::update($_POST['id'], $_POST['name'], $_POST['phone'], $_POST['email'], basename($_FILES['img']['name']), $_POST['course_id']);
                    break;
            }
            header("Location: School.php");
            break;

        case 'add':
            switch ($_POST['type']) {
                case 'course': // Add Course
                    moveImg("../img/Courses/");
                    $course = new Course('', $_POST['name'], $_POST['description'], basename($_FILES['img']['name']));
                    $course->insert();
                    break;

                case 'student': // Add Student
                    moveImg("../img/Students/");
                    $student = new Student('', $_POST['name'], $_POST['phone'], $_POST['email'], basename($_FILES['img']['name']), $_POST['course_id']);
                    $student->insert();
                    break;
            }
            header("Location: School.php");
            break;

        case 'delete':
            switch ($_POST['type']) {
                case 'course': // Delete Course
                    Course::delete($_POST['id']);
                    break;

                case 'student': // Delete Student
                    Student::delete($_POST['id']);
                    break;
            }
            header("Location: School.php");
            break;

        default:
            echo "action is not defined";
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

function EditCourse($id)
{
    $course = Course::get($id);
    $html = LoadBootstrap();
    $html .= LoadScript();
    $html .= "<form enctype=\"multipart/form-data\" action=\"School.php\" method=\"POST\" onSubmit=\"return confirm('Are you sure you want to delete?')\">
                <div class=\"col-sm-12\">
                    <h2>Edit Course</h2>
                    <hr>
                </div>
            
                <div class=\"col-sm-2\">
                    <button type=\"submit\" name=\"action\" value=\"save\" class=\"btn btn-default\" style=\"width:100%;\">Save</button>
                </div>
                <div class=\"col-sm-10\">
                    <button type=\"submit\" name=\"action\" value=\"delete\" class=\"btn btn-default\">Delete</button>
                </div>
            
                <input type=\"hidden\" name=\"type\" value=\"course\">
                <input type=\"hidden\" name=\"id\" value=\"{$id}\">
                
                <div class=\"col-sm-12\"><br></div>
            
                <label class=\"control-label col-sm-2\">Name:</label>
                <div class=\"col-sm-10\">
                    <input type=\"text\" class=\"form-control\" name=\"name\"  value=\"{$course['name']}\" required>
                </div>
            
                <label class=\"control-label col-sm-2\">Description:</label>
                <div class=\"col-sm-10\">
                    <textarea class=\"form-control\" rows=\"5\" name=\"description\">{$course['description']}</textarea>
                </div>
            
                <div class=\"col-sm-12\"><br></div>
            
                <label class=\"control-label col-sm-2\">Image:</label>
                <div class=\"col-sm-2\">
                    <img src=\"../img/Courses/{$course['image']}\" alt=\"course_img\" id=\"editCourseImg\" width=100%>
                </div>
                <div class=\"col-sm-8\">
                    <input type=\"file\" name=\"img\" accept=\"image/*\" onchange=\"changeImg(document.getElementById('editCourseImg'), this)\">
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
    $html .= LoadScript();
    $html .= "<form enctype=\"multipart/form-data\" action=\"School.php\" method=\"POST\">
                <div class=\"col-sm-12\">
                    <h2>Add Course</h2>
                    <hr>
                </div>
            
                <div class=\"col-sm-2\">
                    <button type=\"submit\" name=\"action\" value=\"add\" class=\"btn btn-default\" style=\"width:100%;\">Save</button>
                </div>
                <div class=\"col-sm-10\"></div>
            
                <input type=\"hidden\" name=\"type\" value=\"course\">
            
                <div class=\"col-sm-12\"><br></div>
            
                <label class=\"control-label col-sm-2\">Name:</label>
                <div class=\"col-sm-10\">
                    <input type=\"text\" class=\"form-control\" name=\"name\" required>
                </div>
            
                <label class=\"control-label col-sm-2\">Description:</label>
                <div class=\"col-sm-10\">
                    <textarea class=\"form-control\" rows=\"5\" name=\"description\"></textarea>
                </div>
            
                <div class=\"col-sm-12\"><br></div>
            
                <label class=\"control-label col-sm-2\">Image:</label>
                <div class=\"col-sm-2\">
                    <img src=\"../img/school_img.png\" alt=\"course_img\" id=\"courseImg\" width=100%>
                </div>
                <div class=\"col-sm-8\">
                    <input type=\"file\" name=\"img\" accept=\"image/*\" onchange=\"changeImg(document.getElementById('courseImg'), this)\" required>
                </div>
            </form>
        </body>
        </html>";
    return $html;
}

function EditStudent($id, $courses)
{
    $student = Student::get($id);
    $html = LoadBootstrap();
    $html .= LoadScript();
    $html .= "<form enctype=\"multipart/form-data\" action=\"School.php\" method=\"POST\" onSubmit=\"return confirm('Are you sure you want to delete?')\">
                <div class=\"col-sm-12\">
                    <h2>Edit Student</h2>
                    <hr>
                </div>
            
                <div class=\"col-sm-2\">
                    <button type=\"submit\" name=\"action\" value=\"save\" class=\"btn btn-default\" style=\"width:100%;\">Save</button>
                </div>
                <div class=\"col-sm-10\">
                    <button type=\"submit\" name=\"action\" value=\"delete\" class=\"btn btn-default\">Delete</button>
                </div>
            
                <input type=\"hidden\" name=\"type\" value=\"student\">
                <input type=\"hidden\" name=\"id\" value=\"{$id}\">
            
                <div class=\"col-sm-12\"><br></div>
            
                <label class=\"control-label col-sm-2\">Name:</label>
                <div class=\"col-sm-10\">
                    <input type=\"text\" class=\"form-control\" name=\"name\" value=\"{$student['name']}\" required>
                </div>
            
                <label class=\"control-label col-sm-2\">Phone:</label>
                <div class=\"col-sm-10\">
                    <input type=\"tel\" class=\"form-control\" name=\"phone\" value=\"{$student['phone']}\" required>
                </div>
            
                <label class=\"control-label col-sm-2\">Email:</label>
                <div class=\"col-sm-10\">
                    <input type=\"email\" class=\"form-control\" name=\"email\" value=\"{$student['email']}\" required>
                </div>
            
                <label class=\"control-label col-sm-2\">Image:</label>
                <div class=\"col-sm-2\">
                    <img src=\"../img/Students/{$student['image']}\" alt=\"img\" id=\"editStudentImg\" width=100%>
                </div>
                <div class=\"col-sm-8\">
                    <input type=\"file\" name=\"img\" accept=\"image/*\" onchange=\"changeImg(document.getElementById('editStudentImg'), this)\">
                </div>
            
                <div class=\"col-sm-12\"><br></div>
            
                <label class=\"col-sm-2\">Courses:</label>
                <div class=\"col-sm-10\">";
    $html .= PrintStudentCourses($student['course_id'], $courses);
    $html .= "</div>
            </form>
        </body>
        </html>";
    return $html;
}

function AddStudent($courses)
{
    $html = LoadBootstrap();
    $html .= LoadScript();
    $html .= "<form enctype=\"multipart/form-data\" action=\"School.php\" method=\"POST\">
                <div class=\"col-sm-12\">
                    <h2>Add Student</h2>
                    <hr>
                </div>
            
                <div class=\"col-sm-2\">
                    <button type=\"submit\" name=\"action\" value=\"add\" class=\"btn btn-default\" style=\"width:100%;\">Save</button>
                </div>
                <div class=\"col-sm-10\"></div>
            
                <input type=\"hidden\" name=\"type\" value=\"student\">
            
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
            
                <label class=\"control-label col-sm-2\">Image:</label>
                <div class=\"col-sm-2\">
                    <img src=\"../img/school_img.png\" alt=\"student_img\" id=\"addStudentImg\" width=100%>
                </div>
                <div class=\"col-sm-8\">
                    <input type=\"file\" name=\"img\" accept=\"image/*\" onchange=\"changeImg(document.getElementById('addStudentImg'), this)\" required>
                </div>
            
                <div class=\"col-sm-12\"><br></div>
            
                <label class=\"col-sm-2\">Courses:</label>
                <div class=\"col-sm-10\">";
    $html .= PrintStudentCourses(0, $courses);
    $html .= "</div>  
            </form>
        </body>
        </html>";
    return $html;
}

function PrintStudentCourses($id, $courses)
{
    $html = "";
    $rows = count($courses);
    for ($i = 0; $i < $rows; $i++) {
        $html .= "<label class=\"radio-inline\">
                    <input type=\"radio\" name=\"course_id\" value=\"{$courses[$i]['id']}\" required";
        if ($courses[$i]['id'] === $id)
            $html .= " checked";
        $html .= ">{$courses[$i]['name']}</label>";
    }
    return $html;
}

function ShowCourse($id, $students)
{
    $course = Course::get($id);
    $html = LoadBootstrap();
    $html .= "<form action=\"School.php\">
                <div class=\"col-sm-10\">
                    <h2>{$course['name']}</h2>
                </div>
                <div class=\"col-sm-2\">";

    if ($_SESSION['role'] != "sales")
        $html .= "<button type=\"submit\" class=\"btn btn-default\">Edit</button>";

    $html .= "</div>
        
                <div class=\"col-sm-12\"><hr></div>
        
                <div class=\"col-sm-4\">
                    <img src=\"../img/Courses/{$course['image']}\" alt=\"course_img\" width=100%>
                    
                </div>
                <div class=\"col-sm-8\">
                    <h1>{$course['name']}, " . Student::countCourseStudents($id) . " Student";

    if (Student::countCourseStudents($id) != 1)
        $html .= "s"; // Add s for plural

    $html .= "</h1>
                    <span>{$course['description']}</span>
                </div>
        
                <div class=\"col-sm-12\">
                    <h2>Students</h2>
                </div>";
    $html .= PrintCourseStudents($id, $students);

    $html .= "<input type=\"hidden\" name=\"action\" value=\"edit\">
              <input type=\"hidden\" name=\"type\" value=\"course\">
              <input type=\"hidden\" name=\"id\" value=\"{$id}\"> 
            </form>
        </body>
        </html>";
    return $html;
}

function PrintCourseStudents($id, $students)
{
    $html = "";
    $rows = count($students);
    for ($i = 0; $i < $rows; $i++) {
        if ($students[$i]['course_id'] === $id)
            $html .= "<label class=\"control-label col-sm-12\">{$students[$i]['name']}</label>";
    }
    return $html;
}

function ShowStudent($id)
{
    $student = Student::get($id);
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
                    <img src=\"../img/Students/{$student['image']}\" alt=\"student_img\" width=100%>
                </div>
                <div class=\"col-sm-6\">
                    <h3>{$student['name']}</h3>
                    <h3>{$student['phone']}</h3>
                    <h3>{$student['email']}</h3>
                    <h3>Course: {$student['course']}</h3>
                </div>
                
                <input type=\"hidden\" name=\"action\" value=\"edit\">
                <input type=\"hidden\" name=\"type\" value=\"student\">
                <input type=\"hidden\" name=\"id\" value=\"{$id}\">   
            </form>
        </body>
        </html>";
    return $html;
}

function UploadFile($uploadfile)
{
    echo $uploadfile;

    if (move_uploaded_file($_FILES['img']['tmp_name'], $uploadfile))
        echo "File is valid, and was successfully uploaded";
    else
        echo "Upload failed";

    echo '<pre>';
    echo 'Here is some more debugging info:';
    print_r($_FILES);
    print "</pre>";
}
