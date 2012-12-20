<?php
class User extends Database {

	public $id;
	public $email;
	public $firstname;
	public $lastname;
	public $is_admin;

	/**
	 * Constructor
	 * @param array $array An array with the user's credentials
	 */
	public function __construct($array) {
		$this->id = $array['id'];
		$this->email = $array['email'];
		$this->firstname = $array['firstname'];
		$this->lastname = $array['lastname'];
		$this->is_admin = $array['is_admin'];
	}

    public function fullname() {
        return $this->firstname . " " . $this->lastname;
    }

    public static function find_all_names() {
        $mysqli = self::db_connect();

        $query = 'SELECT id, firstname, lastname from users';

        if($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param('s', $user_id);
            $stmt->execute();
            $stmt->bind_result($id, $firstname, $lastname);
            while($stmt->fetch()) {
                $user = new stdClass();
                $user->id = $id;
                $user->fullname = $firstname.' '.$lastname;

                $users[] = $user;
            }

            return $users;

        }
        else {
            die("Can't create statement: ".$mysqli->error);
        }

    }

	/**
	 * Authenticates a user
	 * @param  string $email    User's email
	 * @param  string $password User's password
	 * @return object           The user or false
	 */
    public static function auth($email, $password) {
        $mysqli = self::db_connect();

        $query = 'SELECT id, email, firstname, lastname, is_admin FROM users WHERE email=? AND password=?';

        if ($stmt = $mysqli->prepare($query)) {
            $password = sha1($password);
        	$stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            $stmt->bind_result($id, $email, $firstname, $lastname, $is_admin);
            $ok = $stmt->fetch();
            $stmt->close();
            $mysqli->close();

            if ($ok) {
                return new User(array(
                	'id' => $id,
                	'email' => $email,
                	'firstname' => $firstname,
                	'lastname' => $lastname,
                	'is_admin' => $is_admin
                	));
            }
            else {
                return FALSE;
            }
        }
        else {
        	die("Can't create statement: " . $mysqli->error);
        }
    }

    /**
     * Find and return the user
     * @param  int 	  $user_id The user's ID
     * @return object          The user's object
     */
    public static function find($user_id) {

    	$mysqli = self::db_connect();

    	$query = 'SELECT id, email, firstname, lastname, is_admin from users WHERE id = ?';

    	if($stmt = $mysqli->prepare($query)) {
    		$stmt->bind_param('s', $user_id);
    		$stmt->execute();
    		$stmt->bind_result($id, $email, $firstname, $lastname, $is_admin);
    		$ok = $stmt->fetch();
    		$stmt->close();
    		$mysqli->close();

    		if ($ok) {
    			return new User(array(
    				'id' => $id,
    				'email' => $email,
    				'firstname' => $firstname,
    				'lastname' => $lastname,
    				'is_admin' => $is_admin
    				));
    		}
    		else {
    			return FALSE;
    		}

    	}
    	else {
    		die("Can't create statement: ".$mysqli->error);
    	}

    }

    /**
     * Get all the events for this user
     * @return array An array of event's object
     */
    public function events() {
    	$events = array();
        $mysqli = self::db_connect();

        $query = "select e.id, e.organizer, e.name, e.description, e.type, e.duration from events e, invitations i where e.id = i.event_id and i.user_id = ? order by name";
        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param("i", $this->id);
            $stmt->bind_result($event_id, $event_organizer, $event_name, $event_description, $event_type, $event_duration);
            $stmt->execute();
            while ($stmt->fetch()) {
                $events[] = new Event(array(
                	'id' => $event_id, 
                	'organizer' => $event_organizer,
                	'name' => $event_name,
                    'description' => $event_description,
                	'type' => $event_type,
                    'duration' => $event_duration
                	));
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