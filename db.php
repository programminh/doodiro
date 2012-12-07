<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "mysql";
$dbname = "doodiro";


function check_credientials($email, $password) {
    $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    $stmt = $mysqli->prepare("select count(*) from (select * from users where email=? and password=?) as t");
    if ($stmt) {
    	$stmt->bind_param("ss", $email, sha1($password));
        $stmt->bind_result($ok);
        $stmt->execute();
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