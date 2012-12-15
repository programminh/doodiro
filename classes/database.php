<?php
class Database {

    protected static function db_connect() {
        require_once('config.php');

        $mysqli = new mysqli($database['hostname'], $database['username'], $database['password'], $database['dbname']);

        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            die();
        }

        return $mysqli;
    }

    // Returns an array containing id, email, firstname and lastname if
    // email matches password, otherwise it returns FALSE.
    public static function check_credientials($email, $password) {
        $mysqli = self::db_connect();

        $query = 'select id, firstname, lastname from users WHERE email=? and password=?';

        if ($stmt = $mysqli->prepare($query)) {
            $password = sha1($password);
        	$stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $stmt->bind_result($id, $firstname, $lastname);
            $ok = $stmt->fetch();
            $stmt->close();
            $mysqli->close();
            if ($ok) {
                return array("id" => $id, "email" => $email, "firstname" => $firstname,
                             "lastname" => $lastname);
            }
            else {
                return FALSE;
            }
        }
        else {
        	die("Can't create statement: " . $mysqli->error);
        }
    }

}