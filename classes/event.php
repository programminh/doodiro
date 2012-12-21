<?php

class Event extends Database {
	public $id;
	public $organizer;
	public $organizer_name;
	public $name;
	public $description;
	public $type;
	public $duration;

	/**
	 * Constructor
	 * @param array $array Array with the event's information
	 */
	public function __construct($array) {
		$this->id = $array['id'];
		$this->organizer = $array['organizer'];
		$this->organizer_name = $array['organizer_name'];
		$this->name = $array['name'];
		$this->description = $array['description'];
		$this->type = $array['type'];
		$this->duration = $array['duration'];
	}

    public static function delete($event_id) {
        $mysqli = self::db_connect();

        $query = "DELETE FROM events WHERE id = {$event_id}";
        $mysqli->query($query);
        $mysqli->close();

        return true;
    }

    /**
     * Return all the events
     * @return array An array of events
     */
    public static function find_all() {
        $mysqli = self::db_connect();

        $query = "SELECT * FROM events";
        $result = $mysqli->query($query);

        while($obj = $result->fetch_object()) {
            $events[] = new Event(array(
                'id' => $obj->id,
                'organizer' => $obj->organizer,
                'organizer_name' => '',
                'name' => $obj->name,
                'description' => $obj->description,
                'type' => $obj->type,
                'duration' => $obj->duration
                ));
        }

        return $events;
    }

    /**
     * Creates a new event
     * The new event array looks like this
     * $new_event = array(
     *     'title' => string,
     *     'organizer_id' => int,
     *     'description' => string,
     *     'duration' => int,
     *     'type' => string,
     *     'invitees' => array('id' => int, 'fullename' => string),
     *     'dates' => array('date' => date, 'fromTime' => time, 'toTime' => time)
     *     );
     * @param  array $new_event Array containing the new event information
     * @return true             True if everything went as expected
     */
    public static function new_event($new_event) {
        $mysqli = self::db_connect();
       
        $organizer_id = $new_event['organizer_id'];
        $name = trim(addslashes($new_event['title']));
        $description = trim(addslashes($new_event['description']));
        $duration = $new_event['duration'];
        $type = $new_event['type'];

        // Create the event
        $query = "INSERT INTO events (organizer, name, description, type, duration) VALUES ({$organizer_id}, '{$name}', '{$description}', '{$type}', {$duration})";
        $mysqli->query($query);

        // Get the new event id
        $new_event_id = $mysqli->insert_id;

        // Link the invitees to the new event
        foreach ($new_event['invitees'] as $invitee) {
            $invitee_id = $invitee['id'];
            $query = "INSERT INTO invitations (user_id, event_id) VALUES ({$invitee_id}, {$new_event_id})";

            $mysqli->query($query);
        }

        // Add the owner to the list of invitee
        $query = "INSERT INTO invitations (user_id, event_id) VALUES({$organizer_id}, {$new_event_id})";
        $mysqli->query($query);

        // Link the dates to the new event
        foreach ($new_event['dates'] as $date) {
            $event_date = $date['date'];
            $start_time = $date['fromTime'];
            $end_time = $date['toTime'];

            $query = "INSERT INTO event_dates (event_id, date, start_time, end_time) VALUES ({$new_event_id}, '{$event_date}', '{$start_time}', '{$end_time}')";

            $mysqli->query($query);
        }

        return true;
    }

	/**
	 * Get the organizer
	 * @return object The organizer
	 */
	public function organizer() {
		return User::find($this->organizer);
	}

    public static function find($id) {
        $mysqli = self::db_connect();
        $query = "select e.organizer, concat(u.firstname, ' ', u.lastname) organizer_name, e.name, e.description, e.type, e.duration from events e, users u where e.id = $id and u.id = e.organizer";
        if ($stmt = $mysqli->query($query)) {
            $obj = $stmt->fetch_object();

            if ($obj) {
                return new Event(array(
                    "id" => $id,
                    "organizer" => $obj->organizer,
                    "organizer_name" => $obj->organizer_name,
                    "name" => $obj->name,
                    "description" => $obj->description,
                    "type" => $obj->type,
                    "duration" => $obj->duration
                ));
            }
            else {
                return FALSE;
            }
        }
    }

    public function userIsInvited($user_id) {
        $mysqli = self::db_connect();
        $query = 'select 1 from invitations where event_id = ? and user_id = ?';
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("ii", $this->id, $user_id);
            $stmt->execute();

            $isInvited = $stmt->fetch();
            $stmt->close();
            $mysqli->close();
            return $isInvited;
        }
        else {
            return FALSE;
        }
    }

    public function getInvitees() {
        $mysqli = self::db_connect();
        $query = "select i.user_id, concat(u.firstname, ' ', u.lastname) fullname from invitations i, users u where event_id = {$this->id} and u.id = i.user_id";
        if ($result = $mysqli->query($query)) {
            $invitees = array();
            while ($obj = $result->fetch_object()) {
                $invitees[] = array($obj->user_id, $obj->fullname);
            }
            $result->close();
            $mysqli->close();
            return $invitees;
        }
        else {
            return FALSE;
        }   
    }

    public function dates() {
        $mysqli = self::db_connect();
        $query = "select id, date, hour(start_time) start_hour, hour(end_time) end_hour from event_dates where event_id = {$this->id} order by date";
        if ($stmt = $mysqli->query($query)) {

            $dates = array();

            while ($obj = $stmt->fetch_object()) {
                $dates[] = array(
                    "id" => $obj->id,
                    "date" => $obj->date,
                    "start_hour" => $obj->start_hour,
                    "end_hour" => $obj->end_hour);
            }

            $stmt->close();
            $mysqli->close();
            return $dates;
        }
        else {
            return FALSE;
        }
    }

    public function getReservationsFor($user_id) {
        $mysqli = self::db_connect();
        $query = <<<ENDSQL
        select ed.date, hour(r.reservation_time) res_time, r.can_go 
        from reservations r, event_dates ed 
        where r.event_date_id = ed.id and ed.event_id = {$this->id} and r.user_id = $user_id
ENDSQL;
        if ($stmt = $mysqli->query($query)) {
            $availabilities = array();
            while ($obj = $stmt->fetch_object()) {
                $availabilities[$obj->date][$obj->res_time] = $obj->can_go;
        }
            $stmt->close();
            $mysqli->close();
            return $availabilities;
        }
        else {
            return FALSE;
        }
    }


    public function deleteAllReservationsFor($user_id) {
        $mysqli = self::db_connect();
        $query = "delete from reservations where user_id = $user_id and event_date_id in (select id from event_dates where event_id = {$this->id})";
        if ($stmt = $mysqli->query($query)) {
            $mysqli->close();
            return TRUE;
        }
        else {
            return FALSE;
        }   
    }

    public function insertAvailabilities($avs) {
        $mysqli = self::db_connect();
        $query = "insert into reservations (user_id, event_date_id, reservation_time, can_go) VALUES $avs";
        $mysqli->query($query);
    }
}
