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
        $query = "select date, hour(start_time) start_hour, hour(end_time) end_hour from event_dates where event_id = {$this->id} order by date";
        if ($stmt = $mysqli->query($query)) {

            $dates = array();

            while ($obj = $stmt->fetch_object()) {
                $dates[] = array(
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
        $query = "delete from reservations where user_id = $user_id and event_dates_id in (select id from event_dates where event_id = {$this->id})";
        if ($stmt = $mysqli->query($query)) {
            $n = $stmt->affected_rows();
            $stmt->close();
            $mysqli->close();
            return $n;
        }
        else {
            return FALSE;
        }   
    }
}
