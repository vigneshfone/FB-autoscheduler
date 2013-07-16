<?php

include_once 'constants.php';

class User {

	const SALT =  'dfsd$%#RFdgfvefew4@!Yhjnjnyi';
	
	public function __construct()
    {
    $connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die('Oops connection error -> ' . mysql_error());
    mysql_select_db(DB_DATABASE, $connection)  or die('Database error -> ' . mysql_error());
    
    }
	

    function insertUser($username, $emailId, $password){
		$query= mysql_query("INSERT INTO `users` (`userID`, `username`, `password`, `email`,`active`,`created`, `modified`) VALUES ('','$username', '$password','$emailId', '1',NOW(), NOW());");
		
		if($query)
		{		
			$query = mysql_query("SELECT * FROM `users` WHERE username = '$username' and password = '$password'");
			$result = mysql_fetch_array($query);
		}else{
			$result = NULL;
		}
        return $result;
	}
	
    function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
	
	function checkUserNormal($username, $password){
        $query = mysql_query("SELECT * FROM `users` WHERE username = '$username' and password = '$password'") or die(mysql_error());
        $result = mysql_fetch_assoc($query);
		if (!empty($result)) {
			return $result;
		}
	}
	
	public function checkForUsername($username){
		$sql = "SELECT * FROM users WHERE username='".$username."'";
		$check_for_username = mysql_query($sql);
		$num_rows = mysql_num_rows($check_for_username);
		return $num_rows;
	}
	
	function makeSaltedHash($password, $salt = '') {
		$password = self::clean($password);
		if (empty($salt)) {
			$salt = makeRandomSalt(mt_rand(64, 128));
		}
		$hash = hash('sha512', $password . $salt . self::SALT);
		for ($i = 0; $i < 50; $i++) {
			$hash = hash('sha512', $password . $salt . self::SALT . $hash);
		}
		return $hash . ':' . $salt;
	}

	function makeRandomSalt($length = 64) {
		$salt = '';
		for ($i = 0; $i < $lenght; $i++) {
			$salt .= chr(mt_rand(33, 126));
		}
		return $salt;
	}

}

?>
