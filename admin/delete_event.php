<?php
require('../bootstrap.php');

echo $_POST['event_id'];
echo Event::delete($_POST['event_id']);