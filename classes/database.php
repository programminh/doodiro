<?php
class Database {

    protected static function db_connect() {
        require('config.php');

        $mysqli = new mysqli($database['hostname'], $database['username'], $database['password'], $database['dbname']);

        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            die();
        }

        if (!$mysqli->set_charset("utf8")) {
		    printf("Error loading character set utf8: %s\n", $mysqli->error);
		    die();
		}

        return $mysqli;
    }

}