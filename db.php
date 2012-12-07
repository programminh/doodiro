<?php
function db_connect() {
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "root";
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

    $query = 'SELECT id, email, firstname, lastname, is_admin FROM users WHERE email=? and password=?';
    
    if ($stmt = $mysqli->prepare($query)) {
    	$stmt->bind_param("ss", $email, sha1($password));
        $stmt->execute();
        $stmt->bind_result($id, $email, $firstname, $lastname, $is_admin);
        $result = $stmt->fetch();
        $stmt->close();
        $mysqli->close();
        return $result;
    }
    else {
    	die("Can't create statement: " . $mysqli->error);
    }
}

?>