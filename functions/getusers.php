<?php
	require_once "constants.php";
	
	$adminUsers = array(); //this would hold the usernames of admin users to alter the select statement slightly
	
	$db = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME); //starts a new instance
	
	//prepares the sql statement
	if ($stmt = $db -> prepare("SELECT username, latitude, longitude FROM user_locations")) {
		$stmt -> execute();
		$stmt -> bind_result($username, $latitude, $longitude); //binds results into variables
		
		$records = array(); //sets up the array for the results
		
		//constructs a json array while results exist
		while ($row = $stmt -> fetch()) {
			$records[] = (array("username" => $username, "latitude" => $latitude, "longitude" => $longitude));
		}
		
		$stmt -> close(); //closes the statement after completion (good for security)
	
	} else {
		echo "Error: " . $db -> error;
		$stmt -> close();
	}
	
	$db -> close(); //closes the database connection for security (no point keeping it open), when user requests a refresh it will open again
	
	echo json_encode($records); //encode the array to json to send back