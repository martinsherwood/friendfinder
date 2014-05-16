<?php
session_start();
require_once "functions/membership.php";

$membership = new membership();

//if the user clicks logout link
if(isset($_GET["status"]) && $_GET["status"] == "logout") {
	$membership -> logOut(); //logs the user out
}

//did the user enter a password/username and click submit if inputs are not empty
if($_POST && !empty($_POST["user"]) && !empty($_POST["password"])) {
	$response = $membership -> validateUser($_POST["user"], $_POST["password"]);
}
?>

<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        
        <link rel="dns-prefetch" href="//use.typekit.net">
        <link rel="dns-prefetch" href="//ajax.googleapis.com">
        <link rel="dns-prefetch" href="//netdna.bootstrapcdn.com">
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Welcome to Friend Finder</title>
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
    
        <div id="wrapper">
            
            <div class="welcome">
            	<h1>Park Campus Friend Finder</h1>
                <div class="welcome-button register-btn">Register</div>
                <div class="welcome-button login-btn">Login</div>
                <p class="intro">You need an account to use the friend finder, if you've already made an account previously just login again.</p>
                
                <div id="registration">
                	<h2>Register</h2>
                    <form id="register-user" method="post" autocomplete="off">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter a username" maxlength="30" required>
                        
                        <label for="useremail">Email</label>
                        <input type="email" id="useremail" name="useremail" placeholder="Enter an email address" required>
                        
                        <label for="userpassword">Password</label>
                        <input type="password" id="userpassword" name="userpassword" placeholder="Enter a password" required>
                        
                        <div id="submit-registration" class="confirm">Register<i class="fa fa-check"></i></div>
                    </form>
                </div>
                
                <div id="login">
                	<h2>Login</h2>
                    <form id="login-user" method="post" autocomplete="off">
                        <label for="user">Username</label>
                        <input type="text" id="user" name="user" placeholder="Enter username" maxlength="30" required>
                        
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter password" required>
                        
                        <div id="submit-login" class="confirm">Login<i class="fa fa-check"></i></div>
                    </form>
                </div>
                
                <?php if (isset($_GET["status"]) && $_GET["status"] == "logout") {echo "<span class=\"message logout\">You have been logged out.</span>";} ?>
                <?php if(isset($response)) echo "<span class=\"message\">" . $response . "</span>"; ?>
                
            </div>
        </div><!--/wrapper-->
        
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-2.1.0.min.js"><\/script>')</script><!--fallback-->
		<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyC6n-3I8KfH6ReeERae16W5M8B1QtzjPGc&sensor=true"></script>
		<script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
        
    </body>
</html>
