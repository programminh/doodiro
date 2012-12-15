<?php 
/**
 * Bootstrapping the application by loading the classes and starting a session
 */

// Loading classes
require('classes/database.php');
require('classes/event.php');
require('classes/user.php');

session_start();