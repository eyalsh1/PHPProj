<?php

include 'api.php';
include 'Header.php';

//echo Admin::printAll();

$admins = Admin::read();
$maxRows = count($admins);

$html = "<div class=\"row\">
            <div class=\"col-sm-2\">
                <div class=\"row\">
                    <div class=\"col-sm-10\"><h4>Administrators</h4></div>
                    <div class=\"col-sm-2\"><button type=\"button\" class=\"btn\">+</button></div>";

                    for ($i = 0; $i < $maxRows; $i++) {
                        $html .= "<div class=\"col-sm-12\">" . buildAdminLink($admins, $i) . "</div>";
                    }

                $html .= "</div>
             </div>
             <div class=\"col-sm-10\" style=\"background-color:lavenderblush;\">.col-sm-8</div>
          </div>";
echo $html;

function buildAdminLink($admins, $i)
{
    $link = "";
    $link .= "<a href=\"?page=admins&admin_id={$admins[$i]['id']}\">";
    $link .= "<figure><img src=\"../img/admins/{$admins[$i]['image']}\" width=100% height=30%>";
    $link .= "<figcaption style=color:blue;>{$admins[$i]["name"]}, {$admins[$i]["role"]}</figcaption>";
    $link .= "<figcaption style=color:blue;>{$admins[$i]["phone"]}</figcaption>";
    $link .= "<figcaption style=color:blue;>{$admins[$i]["email"]}</figcaption></a></figure><br>";
    return $link;
}
