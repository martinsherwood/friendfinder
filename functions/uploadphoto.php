<?php
//start session for error reporting
session_start();

//call connection file
require_once "constants.php";

//check to see if the type of file uploaded is a valid image type
function isValidType($file) {
	//array that holds all the valid image mime types - can be added to as necessary or allow more or even videos etc
	$validTypes = array("image/jpg", "image/jpeg", "image/pjpeg", "image/png", "image/bmp", "image/gif");

	if (in_array($file["type"], $validTypes))
		return 1;
	return 0;
}

//debugging function that prints the contents of the array
function showContents($array) {
	echo "<pre>";
	print_r($array);
	echo "</pre>";
}

//declaration of where to upload the images to
$TARGET_PATH = "../useruploads/";

//get inputs from upload form
$image = $_FILES["image"];
$caption = $_POST["caption"];
$username = $_POST['username'];

//sanitise inputs
$username = htmlentities($_POST["username"], ENT_QUOTES); //this doesn't really need to happen as the username comes from the database (or localStorage) and is already "safe", but we do it anyway to be sure
$caption = htmlentities($_POST["caption"], ENT_QUOTES); //encode any specials chars

//strip the file extension and name for rename (basename is the filename before any changes)
$fileBasename = substr($image["name"], 0, strripos($image["name"], "."));
$fileExtension = substr($image["name"], strripos($image["name"], "."));

//generate a unique ID for the file
$uniqueID = uniqid();

//rename with md5 hashtag and the generated unique ID ("$new_filename" is the new filename to be inserted into the database)
$newFilename = md5($fileBasename) . $uniqueID . $fileExtension;

//clean the filename using regex
$newFilename = preg_replace("/[^\w\.]/", "", strtolower($newFilename));

//build the full string for the target path
$TARGET_PATH .= $newFilename;

//check the fields from the upload form have inputs and customise messages accordingly
if ($caption == "") {
	$_SESSION["error"] = "Your forgot to enter a caption for your photo.";
	header("Location: ../upload.php");
	exit;
} else if ($image == "" || !isValidType($image)) { //if blank or not a valid image from allowed array
	$_SESSION["error"] = "Opps, you didn't select a photo!";
	header("Location: ../upload.php");
	exit;
}

//mimetype check for valid image types
if (!isValidType($image)) {
	$_SESSION["error"] = "You can only upload: jpeg, png, gif, and bmp images.";
	header("Location: ../upload.php");
	exit;
}

//if for some reason the new filename already exists, error here
if (file_exists($TARGET_PATH)) {
	$_SESSION["error"] = "Sorry, a file with that name already exists in the database.";
	header("Location: ../upload.php");
	exit;
}

//attempt to move the file from its temporary directory to its new directory, and insert info into the database
if (move_uploaded_file($image["tmp_name"], $TARGET_PATH)) {
	$db = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME); //start new connection
	
	//do some more sanitisation (real_escape_string is the new MySQLi escape string sanitise method)
	$username = $db -> real_escape_string($username);
	$caption = $db -> real_escape_string($caption);
	
	//prepare the sql statement and insert the new values into the database (the ? represent placeholders)
	if ($stmt = $db -> prepare("INSERT INTO photos (username, filename, caption) VALUES (?, ?, ?)")) {
		$stmt -> bind_param("sss", $username, $newFilename, $caption);
		$stmt -> execute();
		$stmt -> close();
	} else {
		echo "Error: " . $db -> error;
		$stmt -> close();
	}
	
	$db -> close(); //close the db connection
	header("Location: ../photos.php"); //redirect after upload to viewing page
	exit;
	
} else {
	//this could indicate the folder not being writable, but display a generic error to users
	$_SESSION["error"] = "Could not upload file. Please contact mail@martinsherwood.co.uk if this persists.";
	header("Location: ../upload.php");
	exit;
}
?>
