<?php
	
	require_once "constants.php";

	//connect to the database
	$db = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	
	if (isset($_POST)) {
		
		$username = $_POST["username"];
		
		if ($stmt = $db -> prepare("INSERT INTO user_locations (username) VALUES (?)")) {
			$stmt -> bind_param("s", $username);
			$stmt -> execute();
			$stmt -> close();
		} else {
			echo "Error: " . $db -> error;
			$stmt -> close();
		}
		
		$db -> close();
		
	}