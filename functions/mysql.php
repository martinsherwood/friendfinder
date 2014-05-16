<?php

require_once "constants.php";

class mysql {
	private $conn;
	
	function __construct() {
		$this->conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) or 
					  die("There was a problem connecting to the database.");
	}
	
	function verifyUsernamePass($username, $password) {
				
		$query = "SELECT * FROM users WHERE username = ? AND password = ? LIMIT 1"; //limit to 1 for security
		
		//prepare the query
		if($stmt = $this->conn->prepare($query)) {
			$stmt->bind_param("ss", $username, $password); //username and password are both strings
			$stmt->execute(); //execute here
			
			//if result exists, fetch it
			if($stmt->fetch()) {
				$stmt->close(); //then close statement and return true (authorised)
				return true;
			}
		}
	}
}

