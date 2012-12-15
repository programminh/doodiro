<?php

class Event extends Database {
	public $id;
	public $organizer;
	public $name;
	public $start_time;
	public $end_time;
	public $type;

	public function __construct($array) {
		$this->id = $array['id'];
		$this->organizer = $array['organizer'];
		$this->name = $array['name'];
		$this->start_time = $array['start_time'];
		$this->end_time = $array['end_time'];
		$this->type = $array['type'];
	}
}