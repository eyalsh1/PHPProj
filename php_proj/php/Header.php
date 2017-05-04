<?php

include 'api.php';

$html = '<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<style>
figcaption { 
    color: white;
}
</style>
<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <img src="../img/school_img.png" alt="school_img" width="20%">
            </div>
            <ul class="nav navbar-nav">
                <li><a href="#">School</a></li>';
if ( $_SESSION["role"] != "sales")
    $html .= '<li><a href="#">Administration</a></li>';
$html .= '</ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="Logout.php"><span class="glyphicon glyphicon-log-out"></span>Logout</a></li>
                <li><figure><img src="../img/eyal.jpg" alt="manager_img" width="30%";>
                    <figcaption>' . $_SESSION["name"] . ', ' . $_SESSION["role"] . '</figcaption></li>
            </ul>
        </div>
    </nav>
</body>';

echo $html;