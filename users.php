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
        <title>User Locations</title>
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
            
        	<h1>User Locations</h1>
            
            <h2>Usernames</h2>
            <div class="user-locations">
            </div>
             
        </div><!--/wrapper-->
        
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-2.1.0.min.js"><\/script>')</script><!--fallback-->
		<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyC6n-3I8KfH6ReeERae16W5M8B1QtzjPGc&sensor=true"></script>
		<script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
        <script src="js/users.js"></script>
    </body>
</html>
