<?php
include 'ISavable.php';

abstract class Person implements ISavable {
    protected $id;
    protected $name;
    protected $phone;
    protected $email;

    function __construct($id, $name, $phone, $email) {
        $this->id = $id;
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
    }
}