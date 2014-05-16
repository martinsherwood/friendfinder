<?php
require_once "functions/constants.php";	//require the database		
$db = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME); //starts a new instance to get the photos

//checks if a session is authorised or not, if it isn't the user is sent back to index.php to login
require_once "functions/membership.php";
$membership = new membership(); //sets up a new membership instance for this session
$membership -> confirmMember(); //calls the confirm member function that checks the auth
?>

<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        
        <link rel="dns-prefetch" href="//use.typekit.net">
        <link rel="dns-prefetch" href="//ajax.googleapis.com">
        <link rel="dns-prefetch" href="//netdna.bootstrapcdn.com">
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Photos &amp; Images</title>
        <meta name="description" content="University of Gloucestershire, Park Campus, Friend Finder App">
        <meta name="viewport" content="width=device-width, minimum-scale=1.0">
        
        <?php include_once "includes/icons.php" ?>
        
        <script src="//use.typekit.net/nxl2pxo.js"></script>
		<script>try{Typekit.load();}catch(e){}</script>
        
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
		
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
        
    </head>
    <body>
    
        <div id="wrapper" class="inner-wrap">
        	
            <?php include_once "includes/header.php" ?>
            
            <div id="photo-roll">
                <h1>User Photos</h1>
                <div class="upload-more">Upload<i class="fa fa-camera-retro"></i></div>
                
                <div class="photos">
                	<?php
						//prepares the sql statement and execute
						if ($stmt = $db -> prepare("SELECT * FROM photos")) {
							$stmt -> execute();
							$stmt -> bind_result($id, $username, $filename, $caption); //binds results into variables, ready for reading
							
							//for each row retrieved echo out the information
							while ($stmt -> fetch()) {
								//this is where we build the display of the photos, using background and cover sizing for better display
								echo "<div class=\"entry\">";
								echo "<div class=\"img\" style=\"background:url(" . "useruploads/" . $filename . "); background-size: cover\">" . 
										"<div class=\"by\">" . $caption .", by: <span>" . $username . "</span></div>" . //put the username in a slightly darker colour
									 "</div>";
								echo "</div>";
							}
							
							$stmt -> close(); //closes the statement after completion
						
						} else {
							echo "Error: " . $db -> error; //if it errors, go here
							$stmt -> close();
						}
						
						$db -> close(); //closes the database connection
					?>
                </div>
            </div>
        </div><!--/wrapper-->
        
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-2.1.0.min.js"><\/script>')</script><!--fallback-->
		<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyC6n-3I8KfH6ReeERae16W5M8B1QtzjPGc&sensor=true"></script>
		<script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
