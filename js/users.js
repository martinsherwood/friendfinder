
$(function(usersPage) {
	
	var geocoder;
	var address;
	
	//function to get the users from the database who are currently reporting their location
	function getUsers() {
		return $.ajax({
			url: "functions/getusers.php", //this returns a json object containing all the data
			async: true, //might have to be false
			beforeSend: function() {
				console.log("getting users");
			},
			success: function() {
				console.log("success on getting users");
			},
			error: function() {
				console.log("failed to get users");
			},
		});
	};
	
	//this function handles the data retrived from the getsusers.php ajax requests
	function handleUsers(data) {
		
		objects = JSON.parse(data); //parse the json data
		
		//for each record in the data object (this is once per record), iterates through them
		$.each(objects, function(i, item) {
			
			//define new geocodeer and build objects (for later)
			geocoder = new google.maps.Geocoder();
			var latLng = new google.maps.LatLng(item.latitude, item.longitude); //build latlng objects
			
			//for later if I have time - geocode the latlng objects into human readable address format and display near the name
			/*geocoder.geocode({"latLng": latLng}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if (results) {
				  		address = results.formatted_address;
					} else {
				  		console.log("No results found");
					}
			  	} else {
					console.log("Geocoder failed due to: " + status);
			  	};
			});*/
			
			//since all people + their location, including the user are stored in the same table, this override the name or delete the object entirely - not fully working
			if (localStorage.getItem("userName") == item.username) {
				//console.log(this);
				this.username = "You"; //change the username of user to "You"
			}
			
			$(".user-locations").append("<span class=\"username\">" + item.username.toUpperCase() + "</span>"); //make the display
			
			//for later: + "<span class=\"address\">" + results.formatted_address + "</span"
			
		});
	};
	
	getUsers().done(handleUsers); //handle the data

});