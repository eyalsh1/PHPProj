<?php

class DB
{
    private $conn;
    private static $instance;

    private function __construct() {
        $this->conn = new mysqli('localhost', 'root', '', 'php_proj');
        if ($this->conn->errno) {
            echo $this->conn->error;
            die();
        }
    }

    public static function getInstance() {
        if (empty(self::$instance)) {
            return self::$instance = new self();
        } else {
            return self::$instance;
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}

/*
 * Old Vresion
class DB {
    private static $conn;

    public function getConnection() {
        if (empty(static::$conn)) {
            return static::$conn = new mysqli('localhost', 'root', '', 'php_proj');
        } else {
            return static::$conn;
        }
    }

    private function __construct() {}
}
*/