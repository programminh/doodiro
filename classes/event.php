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

	/**
	 * Get the organizer
	 * @return object The organizer
	 */
	public function organizer() {
		return User::find($this->organizer);
	}

    public static function find($id) {
        $mysqli = self::db_connect();
        $query = 'select e.organizer, concat(u.firstname, " ", u.lastname) organizer_name, e.name, e.description, e.type, e.duration from events e, users u where e.id = ? and u.id = e.organizer';
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("i", $id);
            $stmt->bind_result($organizer, $organizer_name, $name, $description, $type, $duration);
            $stmt->execute();
            $ok = $stmt->fetch();
            $stmt->close();
            $mysqli->close();

            if ($ok) {
                return new Event(array(
                    "id" => $id,
                    "organizer" => $organizer,
                    "organizer_name" => $organizer_name,
                    "name" => $name,
                    "description" => $description,
                    "type" => $type,
                    "duration" => $duration
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
        $query = "select i.user_id, concat(u.firstname, ' ', u.lastname) fullname from invitations i, users u where event_id = ? and u.id = i.user_id";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("i", $this->id);
            $stmt->bind_result($user_id, $fullname);
            $stmt->execute();

            $invitees = array();
            while ($stmt->fetch()) {
                $invitees[] = array($user_id, $fullname);
            }
            $stmt->close();
            $mysqli->close();
            return $invitees;
        }
        else {
            return FALSE;
        }   
    }

    public function dates() {
        $mysqli = self::db_connect();
        $query = 'select date, hour(start_time), hour(end_time) from event_dates where event_id = ? order by date';
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("i", $this->id);
            $stmt->bind_result($date, $start_time, $end_time);
            $stmt->execute();

            $dates = array();

            while ($stmt->fetch()) {
                $dates[] = array(
                    "date" => $date,
                    "start_hour" => $start_time,
                    "end_hour" => $end_time);
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
        select ed.date, hour(r.reservation_time), r.can_go 
        from reservations r, event_dates ed 
        where r.event_date_id = ed.id and ed.event_id = ? and r.user_id = ?
ENDSQL;
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("ii", $this->id, $user_id);
            $stmt->bind_result($date, $reservation_time, $can_go);
            $stmt->execute();

            $availabilities = array();
            while ($stmt->fetch()) {
                $availabilities[$date][$reservation_time] = $can_go;
            }
            $stmt->close();
            $mysqli->close();
            return $availabilities;
        }
        else {
            return FALSE;
        }
    }
}
