<?php

class Event extends Database {
	public $id;
	public $organizer;
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
}