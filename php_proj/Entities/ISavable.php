<?php

interface ISavable {
    public function insert();
    public function read();
    public function delete($id);
    public function count();
    //public static function selectAll();
}