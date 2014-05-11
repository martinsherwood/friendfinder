<?php
require_once "functions/membership.php";
$membership = new membership();
$membership -> confirmMember();

//var_dump($_SESSION["username"])

?>

<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        
        <link rel="dns-prefetch" href="//use.typekit.net">
        <link rel="dns-prefetch" href="//ajax.googleapis.com">
        <link rel="dns-prefetch" href="//netdna.bootstrapcdn.com">
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Friend Finder</title>
        <meta name="description" content="University of Gloucestershire, Park Campus, Friend Finder App">
        <meta name="viewport" content="width=device-width, minimum-scale=1.0">
        
        <script src="//use.typekit.net/nxl2pxo.js"></script>
		<script>try{Typekit.load();}catch(e){}</script>
        
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
		
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
        
    </head>
    <body>
    
        <div id="wrapper" class="inner-wrap">
        	
            <?php include_once "includes/header.php" ?>
            
        	<h1>Friend Map</h1>
            
            <div id="friend-map">
            </div>
            
            <div id="user-actions">
                <div class="action locate">Locate Me<i class="fa fa-location-arrow"></i></div>
                
                <div class="action camera">Camera/Photos<i class="fa fa-camera-retro"></i></div>
                
                <div class="action refresh">Refresh<i class="fa fa-refresh"></i></div>
            </div>
            
            <!--
            	to do:
                -------
                
                1. display location of friends on the map in realtime (timed ajax requests)
                
                2. option for user to take/upload photo with a caption
                
                3. refresh option for user to force location check
                
                4. view photos/images page with their captions and who uploaded it
                
                5. build a friends list (if time allows)
            
            -->
            
            
        </div><!--/wrapper-->
        
        
        
        
        
        
        
        
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-2.1.0.min.js"><\/script>')</script><!--fallback-->
		<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyC6n-3I8KfH6ReeERae16W5M8B1QtzjPGc&sensor=true"></script>
		<script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
        
    </body>
</html>
