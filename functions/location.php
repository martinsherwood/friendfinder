<?php
	
	require_once "constants.php";

	//connect to the database
	$db = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	
	if (isset($_POST)) {
		
		$username = $_POST["username"];
		$latitude = $_POST["latitude"];
		$longitude = $_POST["longitude"];
		
		//$username = $db -> real_escape_string($username);
		
		if ($stmt = $db -> prepare("INSERT INTO user_locations (username, latitude, longitude) VALUES (?, ?, ?)")) {
			$stmt -> bind_param("sss", $username, $latitude, $longitude);
			$stmt -> execute();
			$stmt -> close();
		} else {
			echo "Error: " . $db -> error;
			$stmt -> close();
		}
		
		$db -> close();
		
	}