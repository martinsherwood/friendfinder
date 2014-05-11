<?php
require_once "../functions/membership.php";
//$membership = new membership();
//$membership -> confirmMember();
?>

<?php
	//connect to the database
	$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

	//make a form function again
	function renderForm($username = "", $email = "", $password = "", $error = "", $id = "") { ?>
	
	<!DOCTYPE html>
    <html class="no-js">
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
            <title><?php if ($id != "") { echo "Edit User"; } else { echo "New User"; } ?></title>
            <meta name="viewport" content="width=device-width">

            <link rel="stylesheet" href="../css/admin.css">
            
        </head>
        <body>
                    	<div id="adminusers">
							<?php
							echo "<h2>List of Current Users</h2>";
							echo "<hr>";
                                //connect to the database
                                $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
                                
                                //query the database and if entries exist, display them
                                if ($result = $mysqli->query("SELECT * FROM users ORDER BY id")) {
                                    if ($result->num_rows > 0) {
                                        echo "<table>";
                                        echo "<tr><th>ID</th><th>Username</th><th>Email</th></tr>";
                                        while ($row = $result->fetch_object()) {
                                            echo "<tr>";
                                            echo "<td>" . $row->id . "</td>";
                                            echo "<td>" . $row->username . "</td>";
                                            echo "<td>" . $row->email . "</td>";
                                            echo "<td><a href='users.php?id=" . $row->id . "'>Edit User</a></td>";
                                            echo "<td><a href='deleteuser.php?id=" . $row->id . "'>Delete</a></td>";
                                            echo "</tr>";
                                        }
                                        echo "</table>";
                                        echo "<hr>";
                                    } else {
                                        echo "No users have been found."; //show a message if no entries exist
                                    }
                                } else {
                                    echo "Error: " . $mysqli->error; //show an error if there is an issue with the database query
                                }
                                $mysqli->close(); //close database connection
                            ?>
							
							<h2><?php if ($id != "") { echo "Edit User:"; } else { echo "New User:"; } ?></h2>
                                <?php if ($error != "") { echo "<div class=\"error\">" . $error . "</div>";} ?>
                                        <form method="post" class="admin-form validate">
                                        	<?php if ($id != "") { ?>
                                            	<input type="hidden" name="id" value="<?php echo $id; ?>" readonly/>
                                            	<p><em>Currently editing user with the ID of: <strong><?php echo $id; ?></strong>, and username: <strong><?php echo $username; ?></strong></em></p>
                                            <?php } ?>
                                                <label for="username">Username: *</label>
                                                <input type="text" name="username" id="username" value="<?php echo $username; ?>" tabindex="1" required>
                                                
                                                <label for="username">Email: *</label>
                                                <input type="email" name="email" id="email" value="<?php echo $email; ?>" tabindex="2" required>
                                                
                                                <label for="password">Password: *</label>
                                                <input type="text" name="password" id="password" placeholder="New password" value="" tabindex="3" autocomplete="off" required>
                                                
                                                <p>* Required field</p>
                                                <input type="submit" name="submit" value="Submit" tabindex="4">
                                        </form>
                				
        						    <?php }
				
									/*Edit Admin - if the 'id' variable is set in the URL*/
									if (isset($_GET["id"]))
									{
											//process the form
											if (isset($_POST["submit"]))
											{
													//check the id
													if (is_numeric($_POST["id"]))
													{
															//get variables from form
															$id = $_POST["id"];
															$username = htmlentities($_POST["username"], ENT_QUOTES);
															$email = htmlentities($_POST["email"], ENT_QUOTES);
															$password = htmlentities($_POST["password"], ENT_QUOTES);
															
															//make sure fields aren't empty
															if ($username == "" || $password == "" || $email == "") {
																	$error = "Error: Please fill in all required fields.";
																	renderForm($username = "", $email = "", $password = "", $error, $id);
															}
															else {
																	//update the record in the database
																	if ($stmt = $mysqli->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?")) {
																			$stmt->bind_param("sssi", $username, $email, hash("sha512", $password), $id);
																			$stmt->execute();
																			$stmt->close();
																	}
																	//if query error
																	else {
																		echo "Error: could not prepare SQL statement.";
																	}
																	
																	//redirect the user
																	header("Location: users.php");
																	}
																	
																	}
																		//if the 'id' is not valid, show an error message
																		else {
																			echo "Error: Not a valid ID";
																		}
																	}
																	
																	//get information from database
																	else {
																		if (is_numeric($_GET["id"]) && $_GET["id"] > 0) {	
																			$id = $_GET["id"]; //get id from URL
																			
																			//query the database
																			if($stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?")) {
																					$stmt->bind_param("i", $id);
																					$stmt->execute();
																					
																					$stmt->bind_result($id, $username, $email, $password, $date);
																					$stmt->fetch();
																					
																					renderForm($username, $email, $password, NULL, $id);
																					
																					$stmt->close();
																			}
																			else {
																				echo "Error: could not prepare SQL statement";
																			}
																		}
																		//if the 'id' value is not valid, redirect the user back to admin home
																		else {
																			header("Location: users.php");
																		}
																}
														}
														
														/*New Admin - if the 'id' variable is not set in the URL*/
														else {
																//if the form's submit button is clicked, we need to process the form
																if (isset($_POST["submit"])) {
																		//get the form data
																		$username = htmlentities($_POST["username"], ENT_QUOTES);
																		$email = htmlentities($_POST["email"], ENT_QUOTES);
																		$password = htmlentities($_POST["password"], ENT_QUOTES);
																		
																		//check that the fields are not empty
																		if ($username == "" || $email == "" || $password == "") {
																				//if they are empty, show an error message and re-display the form
																				$error = 'Error: Please fill in all required fields.';
																				renderForm($username, $password, $error);
																		}
																		else {
																				//insert into the database
																				if ($stmt = $mysqli->prepare("INSERT users (username, email, password) VALUES (?, ?, ?)")) {
																						$stmt->bind_param("sss", $username, $email, hash('sha512', $password));
																						$stmt->execute();
																						$stmt->close();
																				}
																				//show an error if the query has an error
																				else {
																						echo "Error: Could not prepare SQL statement.";
																				}
																				
																				//redirect the user
																				header("Location: users.php");
																		}
																		
																}
																//if the form hasn't been submitted yet, show the form
																else {
																	renderForm();
																}
														}
														
														//close the connection
														$mysqli->close();
													?>
                            
                         
                    </div><!--/content-->

</body>
</html>
