<?php

class Event extends Database {

	public static function find_for_user($id) {
	        $events = array();
	        $mysqli = self::db_connect();

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

}