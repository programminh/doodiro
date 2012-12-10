<?php
function db_connect() {
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "mysql";
    $dbname = "doodiro";

    $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        die();
    }

    return $mysqli;
}


function check_credientials($email, $password) {
    $mysqli = db_connect();

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

?>
