<?php

require "mysql.php";

class membership {
	
	function validateUser($username, $password) {
		$mysql = new mysql();
		$ensure_credentials = $mysql -> verifyUsernamePass($username, hash("sha512", $password));
		
		if ($ensure_credentials) {
			$_SESSION["status"] = "authorized";
			$_SESSION["username"] = $username;
			header("location: finder.php");
		} else return "Login not recognised, please try again."; //don't be specific for security reasons, it gives hackers more info
		
	} 
	
	//logout 
	function logOut() {
		if(isset($_SESSION["status"])) { //isset returns a true or false
			unset($_SESSION["status"]);
			unset($_SESSION["username"]);
			
			if(isset($_COOKIE[session_name()])) 
				setcookie(session_name(), "", time() + 3600); //automatic login timeout
				session_destroy(); //destroy the session
		}
	}
	
	//if verfied then take the user back
	function confirmMember() {
		session_start();
		if ($_SESSION["status"] != "authorized") header("location: index.php"); //if status isn't auth'd then redirect to login
	}
	
}