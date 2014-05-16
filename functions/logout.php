<?php
	
	require_once "constants.php";

	//connect to the database
	$db = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	
	if (isset($_POST)) {
		
		$username = $_POST["username"];
		
		//remove the users record from the locations table
		if ($stmt = $db -> prepare("DELETE FROM user_locations WHERE username = (?)")) {
			$stmt -> bind_param("s", $username); //bind the username into the prepared statement
			$stmt -> execute(); //execute the statement
			$stmt -> close(); //close the statement
		} else {
			echo "Error: " . $db -> error;
			$stmt -> close(); //if the statement errors, also close it here
		}
		
		$db -> close(); //finally, close the database connect when everything completes / or if an error happens
		
	}