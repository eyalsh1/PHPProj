<?php

$password = 'aaaa';
echo password_hash($password, PASSWORD_DEFAULT);
$hash = '$2y$10$I452XkFSSww4YABkb7QsieRPfB5BOqhKgKESF89lJeGvFWLoYgmwW';
echo password_verify($password, $hash) ? ' verified': ' dont know you';