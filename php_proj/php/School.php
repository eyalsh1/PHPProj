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
$maxRows = max(count($courses), count($students));

$html = "<div class=\"row\">
            <div class=\"col-sm-4\">
                <div class=\"row\">
                    <div class=\"col-sm-5\"><h4>Courses</h4></div>
                    <div class=\"col-sm-1\"><button type=\"button\" class=\"btn\">+</button></div>
                    <div class=\"col-sm-5\"><h4>Students</h4></div>
                    <div class=\"col-sm-1\"><button type=\"button\" class=\"btn\">+</button></div>";

                    for ($i = 0; $i < $maxRows; $i++) {
                        $html .= "<div class=\"col-sm-6\">" . buildCourseLink($courses, $i) . "</div>
                                  <div class=\"col-sm-6\">" . buildStudentLink($students, $i) . "</div>";
                    }

                $html .= "</div>
            </div>
            <div class=\"col-sm-8\" style=\"background-color:lavenderblush;\">.col-sm-8</div>
          </div>";
echo $html;

function buildCourseLink($courses, $i)
{
    $link = "";
    if ($i < count($courses)) {
        $link .= "<a href=\"?page=courses&course_id={$courses[$i]['id']}\">";
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
        $link .= "<a href=\"?page=students&student_id={$students[$i]['id']}\">";
        $link .= "<figure><img src=\"../img/students/{$students[$i]['image']}\" width=100% height=30%>";
        $link .= "<figcaption style=color:blue;>{$students[$i]["name"]}</figcaption>";
        $link .= "<figcaption style=color:blue;>{$students[$i]["phone"]}</figcaption></a></figure><br>";
    }
    return $link;
}
