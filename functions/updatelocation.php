<?php
	
	require_once "constants.php";

	//connect to the database
	$db = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	
	if (isset($_POST)) {
		
		$username = $_POST["username"];
		$longitude = $_POST["longitude"];
		$latitude = $_POST["latitude"];
		
		//"INSERT INTO user_locations (username, latitude, longitude) VALUES (?, ?, ?)"
		
		if ($stmt = $db -> prepare("UPDATE user_locations SET latitude = ?, longitude = ? WHERE username = ?")) { //
			$stmt -> bind_param("sss", $latitude, $longitude, $username);
			$stmt -> execute();
			$stmt -> close();
		} else {
			echo "Error: " . $db -> error;
			$stmt -> close();
		}
		
		$db -> close();
		
	}