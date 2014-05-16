
//this will only run once per user when the login, the "started" flag in localStorage is set
if (!localStorage.getItem("started")) {
	$.ajax({
		type: "POST",
		data: {username: localStorage.getItem("userName")},	//send the username as json
		url: "functions/setname.php",
		async: false, //prevents anything being overwritten, its a fast query so shouldn't affect performance too much
		beforeSend: function() {
			console.log("sending name");
		},
		success: function() {
			console.log("name sent");
			localStorage.setItem("started", "true"); //a value is set to flag a start
		},
	});
};

//main user map function - contains functions for handling map methods (update location, adding markers, drop pin at user location)
$(function(userMap) {
	
	//variables to control certain aspects of the map
	var map, geoMarker; //the map itself and the geoMarker for the users location
	var parkCampus = new google.maps.LatLng(51.886626, -2.088908); //latlng object for the park campus
	var userMarkers = []; //array of user markers when requested
	var userList = $(".users"); //the userlist to populate on refresh of users locations
	
	displayMap(); //map function call
	
	function displayMap() {
		var mapOptions = {
			center: parkCampus,
			zoom: 14,
			disableDefaultUI: true, //remove cluttered ui elements from the map
			mapTypeControl: true, //add a switch for map and sat mode
			mapTypeControlOptions: {
				style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
			},
		};
		
		google.maps.visualRefresh = true;
		
		var map = new google.maps.Map(document.getElementById("user-map"), mapOptions); //displays the map
		
		geoMarker = new GeolocationMarker(); //defines new location marker
		
        geoMarker.setCircleOptions({fillColor: "#808080"}); //draws a circle around users location
		geoMarker.setMinimumAccuracy({accuracy: 100}); //accuracy in metres to acheive before marker is added to the map
		
		//console.log(geoMarker.getPositionOptions()); //get the options currently used by watchPosition, used for testing
		
		//adds an event listener to the geo marker for when their position changes, it updates the marker on the map and sends new coords to database with ajax
        google.maps.event.addListenerOnce(geoMarker, "position_changed", function() {
        	map.setCenter(this.getPosition()); //gets the position from the geoMarker object
        	map.fitBounds(this.getBounds()); //fit the map to new bounds based on the geoMarker objects location data
			console.log("geoMarker: " + this.getPosition()); //testing
			
			var lat = geoMarker.getPosition().lat();
			var lng = geoMarker.getPosition().lng();
			//console.log(lat + ", " + lng); //testing
			
			//post location to database, since this request is in the position_changed event, it will fire each time keeping a users location updated in the database in realtime
			$.ajax({
				type: "POST",
				context: this,
				data: {username: localStorage.getItem("userName"), latitude: lat, longitude: lng},	
				url: "functions/updatelocation.php",
				async: true,
				beforeSend: function() {
					console.log("updating location");
				},
				success: function() {
					console.log("location updated");
				},
			});
        });
		
		//if there's an error getting the users location, we let them know here
        google.maps.event.addListener(geoMarker, "geolocation_error", function(e) {
        	alert("There was an error obtaining your position. Message: " + e.message); //e.message is the specific error message
        });

        geoMarker.setMap(map); //attach the geomarker to the map
		
		//function to drop a marker at the users current location if they request it
		function dropCurrentLocationMarker() {
			var currentLocationMarker = new google.maps.Marker({
				position: geoMarker.getPosition(), //the latlng object from the geoMarker object
				map: map, //set on the usermap
				title: "You are here!", //a small hover title for the marker
			});
		};
		
		//function to get the users from the database who are currently reporting their location
		function getUsers() {
			return $.ajax({
				url: "functions/getusers.php", //this returns a json object containing all the data
				async: true, //might have to be false
				beforeSend: function() {
					console.log("fetching friend locations");
				},
				success: function() {
					console.log("success");
				},
				error: function() {
					console.log("failed to get friends");
				},
			});
		};
		
		//this function handles the data retrived from the getsusers.php ajax requests
		function handleUsers(data) {
			objects = JSON.parse(data); //parse the json data
			
			//for each record in the data object (this is once per record) iterates through them
			$.each(objects, function(i, item) {
				//console.log(item); //testing
				
				//since all people + their location, including the user are stored in the same table, this override the name or delete the object entirely - not fully working
				if (localStorage.getItem("userName") == item.username) {
					console.log(this);
					this.username = "You"; //change the username of user to "You"
					//delete this.username; delete this.latitude; delete this.longitude;
				}
				
				//make google maps latlng objects from the results
				tmpLatLng = new google.maps.LatLng(item.latitude, item.longitude);
				infoWindow = new google.maps.InfoWindow; //define new infoWindows to attach to each marker
				
				//make and place map marker, one for each record in objects
				var userMarker = new google.maps.Marker({
					map: map,
					position: tmpLatLng,
					title: item.username, //title is the username
					animation: google.maps.Animation.DROP, //animate the drops
				});
				
				google.maps.event.addListener(userMarker, "click", function() {
					var winCont = "<p class=\"info-window\">" + item.username.toUpperCase() + "</p>"; //put the username of the users into the marker and make it uppercase for better and consistent presentation
					infoWindow.setContent(winCont); //set the infowindow content to the string above
					infoWindow.open(map, userMarker); //on click, open the infowindow
				});
				
				//push markers into the array
				userMarkers.push(userMarker);
				
				userList.append("<li>" + item.username.toUpperCase() + "</li>"); //finally append usernames into a list
				
			});
		};
		
		$(".drop-pin").click(function(e) {
			dropCurrentLocationMarker(); //calls the drop pin at current location function
		});
		
		$(".refresh-users").click(function(e) {
			userList.html(""); //clear list
			getUsers().done(handleUsers); //handle the data
		});
	};
	
	//manually update location in the database (works in the same way as before, except the lat and lng are extracted from the geoMarker object)
	$(".update-location").click(function(e) {
		var lat = geoMarker.getPosition().lat();
		var lng = geoMarker.getPosition().lng();
		
		$.ajax({
			type: "POST",
			context: this,
			data: {username: localStorage.getItem("userName"), latitude: lat, longitude: lng},	
			url: "functions/updatelocation.php",
			async: false,
			beforeSend: function() {
				$(this).css("background", "#444"); //change background when sending
				console.log("updating location");
			},
			success: function() {
				console.log("location updated");
			},
		});
		
		setTimeout(function() {
			$(".update-location").css("background", "#00adef"); //set back after send
		}, 2500);
	});
	
	//go to the photos page
	$(".upload-photos").click(function(e) {
		window.location.href = "upload.php"; //since the hasn't got an href attribute, link it up here
	});
});