
//this function contains the drop down menu that is present on the finder.php page
$(function(mainMenu) {
	var menu = $(".main-menu");
	
	$("#menu-button").click(function(e) {
        toggleMenu(); //toggle the menu
    });
	
	//define the actions as a function in case we need it in multiple places
	var toggleMenu = function(e) {
		menu.toggleClass("menu-open");
		$("#menu-button").toggleClass("push-down");
		$("i", this).toggleClass("fa-flip-vertical");
	}
});

//small function to show and hide the forms on the index.php page (no point showing both at the same time)
$(function(displayForms) {
	$(".register-btn").click(function(e) {
		$(".message").remove(); //remove logout and other messages if present
		
		if ($("#login").hasClass("form-shown")) {
			$("#login").removeClass("form-shown");
		};
		
        $("#registration").toggleClass("form-shown");
    });
	
	$(".login-btn").click(function(e) {
		$(".message").remove(); //remove logout and other messages if present
		
		if ($("#registration").hasClass("form-shown")) {
			$("#registration").removeClass("form-shown");
		};
		
        $("#login").toggleClass("form-shown");
    });
});

//logout functions, removes values from localStorage as required
/*$(function(logoutUser) {
	$(".logout").click(function(e) {
        //localStorage.removeItem("userName");
		//localStorage.removeItem("started");
    });
});*/

//this function handles the registration of users and submits the login form when posted
$(function(loginRegister) {
	$("#submit-registration").click(function(e) {
		e.stopPropagation(); e.preventDefault();
		$("#register-user").submit();
	});
	
	$("#register-user").submit(function(e) {
		e.stopPropagation(); e.preventDefault();
		
		$(".error").remove(); //remove any errors if present
		
		//get the values from the inputs
		var userName 	 = $("#username").val()
		var userEmail	 = $("#useremail").val()
		var userPassword = $("#userpassword").val()
		
		var userRegistration = $(this).serialize(); //used for db, the password is hashed server side (more secure)
		
		//first validate the inputs
		if (userName == "" ) {
			$("#username").focus();
			$("#username").attr("placeholder", "Username is required") && $("#username").addClass("plc-error");
			setTimeout(function() {
				$("#username").attr("placeholder", "Enter a username") && $("#username").removeClass("plc-error");
			}, 3000);
			return false;
			
		} else if (userName.match(/[_\W0-9]/)) { //check for spaces and special chars, could use indexOf() but since we are checking for many characters we just use a quick regular expression
			$("#register").append("<span class=\"block-error\">Username cannot contain spaces or punctuation.</span>");
			return false;
			
		} else if (userEmail == "") {
			$("#useremail").focus();
			$("#useremail").attr("placeholder", "Email address is required") && $("#useremail").addClass("plc-error");
			setTimeout(function() {
				$("#useremail").attr("placeholder", "Enter an email address") && $("#useremail").removeClass("plc-error");
			}, 3000);
			return false;
			
		} else if (userPassword == "") {
			$("#userpassword").focus();			
			$("#userpassword").attr("placeholder", "Password is required") && $("#userpassword").addClass("plc-error");
			setTimeout(function() {
				$("#userpassword").attr("placeholder", "Enter a password") && $("#userpassword").removeClass("plc-error");
			}, 3000);
			return false;
		};
		
		$.ajax({
			type: "POST",
			data: userRegistration,	//send the data to the db
			url: "functions/register.php",
			async: true,
			beforeSend: function() {
				$(".intro").append("<p class=\"message\">Creating your account... <i class=\"fa fa-spinner fa-spin\"></i></p>");
				console.log("creating");
			},
			success: function() {
				$(".message").html("Your acount has been created, you can now login.");
				$("#registration").removeClass("form-shown");
				$("#username").val("") && $("#useremail").val("") && $("#userpassword").val("");
				console.log("registered");
			},
		});
	});
	
	//login submit handler
	$("#submit-login").click(function(e) {
		$("#login-user").submit();
	});
	
	$("#login-user").submit(function(e) {
		localStorage.setItem("userName", $("#user").val());
		//console.log($("#user").val()); //testing
		
		localStorage.setItem("started", "true"); //a value is set to prevent this function from running over and over again
	});
});


//this function gets the location of the user and sends it to the database with ajax, it only runs once per user
$(function(initialLocate) {
	//first check if this has been done already
	if (!localStorage.getItem("started")) {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function(position) {
				var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude); //build a new object if we need it
				console.log(position.coords.latitude + ", " + position.coords.longitude); //testing
				
				//localStorage.setItem("started", "true"); //a value is set to prevent this function from running over and over again
				
				$.ajax({
					type: "POST",
					context: this,
					data: {username: localStorage.getItem("userName"), latitude: position.coords.latitude, longitude: position.coords.longitude},	
					url: "functions/location.php",
					async: false,
					beforeSend: function() {
						console.log("sending initial location");
					},
					success: function() {
						console.log("initial location sent");
					},
				});	
			});
		} else {
			alert("Geolocation is currently not available.");
		};
	};
});

$(function(friendMap) {
	//test locations
	/*var f1 = new google.maps.LatLng(45.117225, 5.877747);
	var f2 = new google.maps.LatLng(45.468323, 6.905579);
	var f3 = new google.maps.LatLng(48.070081, 6.877292);
	var f4 = new google.maps.LatLng(46.773705, 6.350552);*/
	
	var map, geoMarker;
	var parkCampus = new google.maps.LatLng(51.886626, -2.088908);
	var mapStyles = [{"elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"color":"#f5f5f2"},{"visibility":"on"}]},{"featureType":"administrative","stylers":[{"visibility":"off"}]},{"featureType":"transit","stylers":[{"visibility":"off"}]},{"featureType":"poi.attraction","stylers":[{"visibility":"off"}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"visibility":"on"}]},{"featureType":"poi.business","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","stylers":[{"visibility":"off"}]},{"featureType":"poi.place_of_worship","stylers":[{"visibility":"off"}]},{"featureType":"poi.school","stylers":[{"visibility":"off"}]},{"featureType":"poi.sports_complex","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#ffffff"},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"visibility":"simplified"},{"color":"#ffffff"}]},{"featureType":"road.highway","elementType":"labels.icon","stylers":[{"color":"#ffffff"},{"visibility":"off"}]},{"featureType":"road.highway","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.arterial","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","stylers":[{"color":"#ffffff"}]},{"featureType":"poi.park","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"water","stylers":[{"color":"#71c8d4"}]},{"featureType":"landscape","stylers":[{"color":"#e5e8e7"}]},{"featureType":"poi.park","stylers":[{"color":"#8ba129"}]},{"featureType":"road","stylers":[{"color":"#ffffff"}]},{"featureType":"poi.sports_complex","elementType":"geometry","stylers":[{"color":"#c7c7c7"},{"visibility":"off"}]},{"featureType":"water","stylers":[{"color":"#a0d3d3"}]},{"featureType":"poi.park","stylers":[{"color":"#91b65d"}]},{"featureType":"poi.park","stylers":[{"gamma":1.51}]},{"featureType":"road.local","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"visibility":"on"}]},{"featureType":"poi.government","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"landscape","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"road.local","stylers":[{"visibility":"simplified"}]},{"featureType":"road"},{"featureType":"road"},{},{"featureType":"road.highway"}];
	
	/*var dropPinBlue = "img/location-pin-blue.png";
		dropPinGreen = "img/location-pin-green.png";
		dropPinPink = "img/location-pin-pink.png";
		dropPinOrange = "img/location-pin-orange.png";
		dropPinRed = "img/location-pin-red.png";*/
		
	//var friendInfoWindow = "<div id=\"friend-window\">" + "</div>";
	
	displayMap(); //map function call
	
	function displayMap() {
		var mapOptions = {
			center: parkCampus,
			zoom: 16,
			disableDefaultUI: true,
			mapTypeControl: true,
			mapTypeControlOptions: {
				style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
			},
			//styles: mapStyles
		};
		
		google.maps.visualRefresh = true;
		
		var map = new google.maps.Map(document.getElementById("friend-map"), mapOptions); //displays the map
		
		geoMarker = new GeolocationMarker(); //defines new location marker
		
        geoMarker.setCircleOptions({fillColor: "#808080"}); //draws a circle around users location
		geoMarker.setMinimumAccuracy({accuracy: 100}); //accuracy in metres to acheive before marker is added to the map
		
		//get the options currently used by watchPosition
		//console.log(geoMarker.getPositionOptions());
		
		//adds an event listener to the geo marker for when their position changes, it updates the marker on the map and sends new coords to database with ajax
        google.maps.event.addListenerOnce(geoMarker, "position_changed", function() {
        	map.setCenter(this.getPosition());
        	map.fitBounds(this.getBounds());
			console.log("geoMarker: " + this.getPosition()); //testing
			
			var lat = geoMarker.getPosition().lat();
			var lng = geoMarker.getPosition().lng();
			console.log(lat + ", " + lng); //testing
			
			$.ajax({
				type: "POST",
				context: this,
				data: {username: localStorage.getItem("userName"), latitude: lat, longitude: lng},	
				url: "functions/updatelocation.php",
				async: false,
				beforeSend: function() {
					console.log("updating location");
				},
				success: function() {
					console.log("location updated");
				},
			});
        });
		
		//adjusts zoom level based on new position
		/*google.maps.event.addListenerOnce(map, "bounds_changed", function(event) {
			if (this.getZoom() > 15){
				this.setZoom(16);
			}
		});*/
		
		//if there's an error getting the users location, we let them know
        google.maps.event.addListener(geoMarker, "geolocation_error", function(e) {
        	alert("There was an error obtaining your position. Message: " + e.message);
        });

        geoMarker.setMap(map);
		
		//after moving the map, friend locations are updated
		google.maps.event.addListener(map, "dragend", function() {
			//updateFriendLocations(); //for later
		});
	};
	
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
				console.log("updating location");
			},
			success: function() {
				console.log("location updated");
			},
		});
	});
	
	//animation: google.maps.Animation.DROP,
});