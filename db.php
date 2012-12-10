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


// Returns an array containing id, email, firstname and lastname if
// email matches password, otherwise it returns FALSE.
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

function events_for_user($id) {
    $events = array();
    $mysqli = db_connect();

    $query = "select e.id, e.name from events e, invitations i where e.id = i.event_id and i.user_id = ? order by name";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $id);
        $stmt->bind_result($event_id, $event_name);
        $stmt->execute();
        while ($stmt->fetch()) {
            $events[] = array("id" => $event_id, "name" => $event_name);
        }
        $stmt->close();
        $mysqli->close();
        return $events;
    }
    else {
        die("Can't create statement: " . $mysqli->error);
    }
}

?>
