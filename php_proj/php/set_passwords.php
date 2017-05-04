<?php

$password = 'itai';
echo password_hash($password, PASSWORD_DEFAULT);
$hash = '$2y$10$YVvVn1bSukpbzfWecY51PezhUfVLN1m/FCPiP8DxXGjjP/ySnSjye';
echo password_verify($password, $hash) ? ' verified': ' dont know you';