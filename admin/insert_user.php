<?php
include('../bootstrap.php');

$new_user = array(
	'email' => $_POST['email'],
	'password' => $_POST['password'],
	'firstname' => $_POST['firstname'],
	'lastname' => $_POST['lastname']
	 );

echo User::insert($new_user);
