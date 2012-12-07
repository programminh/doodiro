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

    $query = 'SELECT count(*) FROM (select 1 from users WHERE email=? and password=?) as t';

    if ($stmt = $mysqli->prepare($query)) {
        $password = sha1($password);
    	$stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $stmt->bind_result($ok);
        $stmt->fetch();
        $stmt->close();
        $mysqli->close();
        return $ok;
    }
    else {
    	die("Can't create statement: " . $mysqli->error);
    }
}

?>