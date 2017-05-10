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
        case 'edit':
            switch ($_GET['type']) {
                case 'course':
                    $html .= "<div class=\"col-sm-8\">";
                    $html .= file_get_contents('../templates/AddEditCourse.html');
                    $html .= "</div>";
                    break;

                case 'student':
                    $html .= "<div class=\"col-sm-8\">";
                    $html .= file_get_contents('../templates/AddEditStudent.html');
                    $html .= "</div>";
                    break;
            }
            break;

        /*case 'edit':
            switch ($_GET['type']) {
                case 'course':
                    $id = $_GET['id'];
                    $html .= "<div class=\"col-sm-8\">";
                    $html .= file_get_contents('../templates/AddEditCourse.html');
                    $html .= "</div>";
                    break;

                case 'student':
                    $id = $_GET['id'];
                    $html .= "<div class=\"col-sm-8\">";
                    $html .= file_get_contents('../templates/AddEditStudent.html');
                    $html .= "</div>";
                    break;
            }
            break;*/

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
                    <div class=\"col-sm-12\" style=\"color:red;\"><h1>School summary</h1></div>
                    <div class=\"col-sm-12\" style=\"color:red;\"><h2>Courses amount is " . count($courses) . "</h2></div>
                    <div class=\"col-sm-12\" style=\"color:red;\"><h2>Students amount is " . count($students) . "</h2></div>
                </div>
             </div>";
    return $html;
}

function buildCourseLink($courses, $i)
{
    $link = "";
    if ($i < count($courses)) {
        $link .= "<a href=\"?action=edit&type=course&id={$courses[$i]['id']}\">";
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
        $link .= "<a href=\"?action=edit&type=student&id={$students[$i]['id']}\">";
        $link .= "<figure><img src=\"../img/students/{$students[$i]['image']}\" width=100% height=30%>";
        $link .= "<figcaption style=color:blue;>{$students[$i]["name"]}</figcaption>";
        $link .= "<figcaption style=color:blue;>{$students[$i]["phone"]}</figcaption></a></figure><br>";
    }
    return $link;
}