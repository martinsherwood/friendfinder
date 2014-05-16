
var USERNAME = localStorage.getItem("userName");

//this function contains the drop down menu that is present on the finder.php page
$(function(mainMenu) {
	var menu = $(".main-menu");
	
	$("#menu-button").click(function(e) {
        toggleMenu(); //toggle the menu
		$("i", this).toggleClass("fa-flip-vertical"); //flip the arrow
    });
	
	//define the actions as a function in case we need it in multiple places
	var toggleMenu = function(e) {
		menu.toggleClass("menu-open");
		$("#menu-button").toggleClass("push-down");
	}
});

//small function to show and hide the forms on the index.php page (no point showing both at the same time)
$(function(displayForms) {
	$(".register-btn").click(function(e) {
		$(".message").remove(); //remove logout and other messages if present
		
		//show or hide the login form
		if ($("#login").hasClass("form-shown")) {
			$("#login").removeClass("form-shown");
		};
		
        $("#registration").toggleClass("form-shown");
    });
	
	$(".login-btn").click(function(e) {
		$(".message").remove(); //remove logout and other messages if present
		
		//show or hide the register form
		if ($("#registration").hasClass("form-shown")) {
			$("#registration").removeClass("form-shown");
		};
		
        $("#login").toggleClass("form-shown");
    });
});


//this function handles the registration of users and submits the login form when posted
$(function(loginRegister) {
	$("#submit-registration").click(function(e) {
		e.stopPropagation(); e.preventDefault(); //prevent default and event bubbling
		$(".failed").remove(); //remove any previous failed messages if present
		$("#register-user").submit();
	});
	
	$("#register-user").submit(function(e) {
		e.stopPropagation(); e.preventDefault(); //these calls stop the default submit action on the form, since it is overided with the ajax below
		
		$(".error").remove(); //remove any errors if present
		
		//get the values from the inputs, really this doesn't need to be done because I serialize it below anyway, but easy to to validation with individual values
		var userName 	 = $("#username").val()
		var userEmail	 = $("#useremail").val()
		var userPassword = $("#userpassword").val()
		
		var userRegistration = $(this).serialize(); //used for db, the password is hashed server side (more secure)
		
		//first validate the inputs
		
		//if the username is empty, focus back and display an error - this happens for each of the inputs
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
		
		//if all inputs are okay, perform this ajax request
		$.ajax({
			type: "POST",
			data: userRegistration,	//send the data to the db
			url: "functions/register.php",
			async: true,
			beforeSend: function() {
				$(".intro").append("<p class=\"message\">Creating your account... <i class=\"fa fa-spinner fa-spin\"></i></p>"); //display a working message
				console.log("creating");
			},
			success: function() {
				$(".message").html("Your acount has been created, you can now login."); //change the message when complete and successful
				$("#registration").removeClass("form-shown"); //hide the form from view again
				$("#username").val("") && $("#useremail").val("") && $("#userpassword").val(""); //this clears the inputs on the form
				console.log("registered");
			},
			error: function() {
				$(".intro").append("<p class=\"message failed\">An error occured making your account, please try again.</p>"); //display an error message
				console.log(this); //log the error in the console
			},
		});
	});
	
	//login submit handler
	$("#submit-login").click(function(e) {
		$("#login-user").submit();
	});
	
	$("#login-user").submit(function(e) {
		localStorage.setItem("userName", $("#user").val());
	});
	
	//we only want to track users when they are logged in and active in the application, so this function removes any location data previously sent to the database by the user or automatically
	$(".logout").click(function(e) {
		$.ajax({
			type: "POST",
			data: {username: localStorage.getItem("userName")},	
			url: "functions/logout.php", //call the script
			async: false,
			beforeSend: function() {
				console.log("logging out");
			},
			success: function() {
				console.log("logged out, location data removed");
				localStorage.removeItem("started"); //remove the start flag
			},
		});
    });
});


//this function gets the location of the user and sends it to the database with ajax, it only runs once per user
//this function is no longer used because after further investigating watchPosition, it is possible to access the individual
//lat and lng via that function in finder.js, so location is sent there instead
/*$(function(initialLocate) {
	//first check if this has been done already
	if (localStorage.getItem("started")) {
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
});*/


$(function(photoUpload) {
	
	$("#username").val(USERNAME); //populate the hidden username field with the username of the user
	$("#image").on("change", preparePhotos); //when the image input changes, it calls preparePhotos
	
	//grab the files and set them to the photos variable
	function preparePhotos(e) {
		var photos = e.target.files;
		console.log(photos);
	};
	
	$(".upload-button").click(function(e) {
		$("#upload-photo").submit();
    });
	
	//go to the upload page
	$(".upload-more").click(function(e) {
		window.location.href = "upload.php"; //since the hasn't got an href attribute, link it up here
	});
});