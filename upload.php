<?php
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
        <title>Upload a Photo</title>
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
            
        	<h1>Upload a Photo</h1>
            
            <form id="upload-photo" action="functions/uploadphoto.php" method="post" enctype="multipart/form-data">
            	<label for="image">Choose or Take a Photo</label>
            	<input name="image" id="image" type="file" required><!--add multiple attribute to allow multiple image uploads at once, out of scope here as we are using captions-->
                
                <label for="caption">Caption</label>
                <input type="text" id="caption" name="caption" placeholder="Enter a caption" maxlength="120" required>
                
                <input type="hidden" id="username" name="username"> <!--hidden username field, value is inserted into database with filename and caption-->
                
                <div class="upload-button">Upload<i class="fa fa-cloud-upload"></i></div>
            </form>
            
            <?php
				if (isset($_SESSION["error"])) {
					echo "<span id=\"error\">" . $_SESSION["error"] . "</span>";
					unset($_SESSION["error"]);
				}
			?>
             
        </div><!--/wrapper-->
        
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-2.1.0.min.js"><\/script>')</script><!--fallback-->
		<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyC6n-3I8KfH6ReeERae16W5M8B1QtzjPGc&sensor=true"></script>
		<script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
        
    </body>
</html>
