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

    /**
     * Find all the users except the administrator
     * @return [type] [description]
     */
    public static function find_all() {
        $mysqli = self::db_connect();
        $query = "SELECT * FROM users WHERE is_admin <> 1";

        $result = $mysqli->query($query);

        while($obj = $result->fetch_assoc()) {
            $users[] = new User($obj);
        }

        $mysqli->close();
        return $users;
    }

    /**
     * Return the name and the if off all the users except
     * the logged in user
     * @param  [type] $except_owner [description]
     * @return [type]               [description]
     */
    public static function find_all_names($logged_in_user_id) {
        $mysqli = self::db_connect();

        $query = "SELECT id, firstname, lastname from users WHERE id <> $logged_in_user_id";

        if($result = $mysqli->query($query)) {
            while($obj = $result->fetch_object()) {
                $user = new stdClass();
                $user->id = $obj->id;
                $user->fullname = $obj->firstname.' '.$obj->lastname;

                $users[] = $user;
            }
            $mysqli->close();
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
        $password = sha1($password);
        $query = "SELECT id, email, firstname, lastname, is_admin FROM users WHERE email='$email' AND password='$password'";

        if ($stmt = $mysqli->query($query)) {
            $obj = $stmt->fetch_object();
            $stmt->close();
            $mysqli->close();

            if ($obj) {
                return new User(array(
                	'id' => $obj->id,
                	'email' => $obj->email,
                	'firstname' => $obj->firstname,
                	'lastname' => $obj->lastname,
                	'is_admin' => $obj->is_admin
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

    	$query = "SELECT id, email, firstname, lastname, is_admin from users WHERE id = $user_id";

    	if($result = $mysqli->query($query)) {
    		$obj = $result->fetch_object();
    		$mysqli->close();

    		if ($obj) {
    			return new User(array(
    				'id' => $obj->id,
    				'email' => $obj->email,
    				'firstname' => $obj->firstname,
    				'lastname' => $obj->lastname,
    				'is_admin' => $obj->is_admin
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

    public function count_events() {
        $mysqli = self::db_connect();
        $query = "select count(id) as count from events where organizer = {$this->id}";
        $result = $mysqli->query($query);

        $obj = $result->fetch_object();

        $mysqli->close();

        return $obj->count;
    }

    /**
     * Get all the events for this user
     * @return array An array of event's object
     */
    public function events() {
    	$events = array();
        $mysqli = self::db_connect();
        $query = "select e.id, e.organizer, concat(u.firstname, ' ', u.lastname) as fullname, e.name, e.description, e.type, e.duration from events e, invitations i, users u where e.id = i.event_id and i.user_id = {$this->id} and e.organizer = u.id order by name";
        if ($stmt = $mysqli->query($query)) {
            while ($obj = $stmt->fetch_object()) {
                $events[] = new Event(array(
                	'id' => $obj->id, 
                	'organizer' => $obj->organizer,
                	'organizer_name' => $obj->fullname,
                	'name' => $obj->name,
                    'description' => $obj->description,
                	'type' => $obj->type,
                    'duration' => $obj->duration
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

    public static function delete($id) {
        $mysqli = self::db_connect();

        $query = "DELETE FROM users WHERE id = {$id}";
        $mysqli->query($query);
        $mysqli->close();

        return true;
    }

    public static function insert($new_user) {
        $mysqli = self::db_connect();
        $email = trim($new_user['email']);
        $password = sha1(trim($new_user['password']));
        $firstname = trim($new_user['firstname']);
        $lastname = trim($new_user['lastname']);
        $query = "insert into users (email, password, firstname, lastname) values('$email', '$password', '$firstname', '$lastname')";
        $mysqli->query($query);
        $mysqli->close();
        return true;
    }
}
