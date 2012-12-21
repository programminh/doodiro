<?php
require('../bootstrap.php');

echo $_POST['user_id'];
echo User::delete($_POST['user_id']);
