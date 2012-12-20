<?php
class Database {

    protected static function db_connect() {
        require('config.php');
        $mysqli = new mysqli($database['hostname'], $database['username'], $database['password'], $database['dbname']);

        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            die();
        }

        $mysqli->query("SET NAMES utf8");  

        return $mysqli;
    }

}
