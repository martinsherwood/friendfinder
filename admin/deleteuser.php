<?php

require_once "../functions/constants.php";

	//connect to the database
	$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
	     
    	//confirm that the id variable has been set
    	if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
			
        	//get the id variable from the URL
            $id = $_GET["id"];
                
            	//delete record from database
                if ($stmt = $mysqli -> prepare("DELETE FROM users WHERE id = ? LIMIT 1")) {
                	$stmt -> bind_param("i", $id);     
                    $stmt -> execute();
                    $stmt -> close();
                }
				
			else {
				echo "Error: could not prepare SQL statement.";
			}
				
            $mysqli->close();	
            	header("Location: users.php"); //redirect user after delete is successful
			}
			
        	else {
                header("Location: users.php"); //if the 'id' variable isn't set, redirect the user
        }

?>