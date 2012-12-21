<?php
include('../bootstrap.php');
$user = $_SESSION['user'];

$new_event = array(
	'title' => $_POST['title'],
	'organizer_id' => $user->id,
	'description' => $_POST['description'],
	'duration' => $_POST['duration'],
	'type' => $_POST['type'],
	'invitees' => $_POST['invitees'],
	'dates' => $_POST['dates']
	 );

echo Event::new_event($new_event);