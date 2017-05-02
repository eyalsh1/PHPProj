<?php

$password = 'orit';

echo password_hash($password, PASSWORD_DEFAULT);

$hash = '$2y$10$0OLgF5dpccvDOKrN5Aq2r.LIUWFdaJjjJ5xFd492FPS3qgA3oVIHO';

echo password_verify($password, $hash) ? ' verified': ' dont know you';