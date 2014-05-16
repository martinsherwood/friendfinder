<?php
	
	require_once "constants.php";

	//connect to the database
	$db = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	
	if (isset($_POST)) {
		
		//sanitise inputs
		$username = htmlentities($_POST["username"], ENT_QUOTES);
		$useremail = htmlentities($_POST["useremail"], ENT_QUOTES);
		$userpassword = htmlentities($_POST["userpassword"], ENT_QUOTES);
		
		//server side check for blank inputs
		if ($username == "" || $useremail == "" || $userpassword == "") {
			return false;
			header("location: index.php");
		}
		
		//escape characters
		$username = $db -> real_escape_string($username);
		$useremail = $db -> real_escape_string($useremail);
		$userpassword = $db -> real_escape_string($userpassword);
		
		if ($stmt = $db -> prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)")) {
			$stmt -> bind_param("sss", $username, $useremail, hash("sha512", $userpassword));
			$stmt -> execute();
			$stmt -> close();
		} else {
			echo "Error: " . $db -> error;
			$stmt -> close();
		}
		
		$db -> close();
		
	}