<?php
require 'usersClass.php';

if(isset($_POST['username']))//If a username has been submitted
{
$username = mysql_real_escape_string($_POST['username']);//Some clean up :)
$user = new User();
$num_rows = $user->checkForUsername($username);

if($num_rows > 0)
{

echo '1';//If there is a  record match in the Database - Not Available
}
else
{

echo '0';//No Record Found - Username is available
}
}
?>