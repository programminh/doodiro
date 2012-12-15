<?php
/**
 * Destroys session and logs out
 */

session_start();

// Resets the session's data
$_SESSION = array();

session_destroy();

header('Location: login.php');