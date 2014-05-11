<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Password Generate</title>
</head>

<body>

<h1>Random salt method</h1>
<p>Hit refresh (F5) to see random salts working</p>
<?php
//this works to generate a random salt and hash it with the password

$password = "admin"; //change this to whatever you want the password to be

//seed with microseconds
function makeSeed() {
  list($usec, $sec) = explode(' ', microtime());
  return (float) $sec + ((float) $usec * 100000);
}
	//get a random value using makeSeed
	mt_srand(makeSeed());
	$randval = mt_rand();
	
	//put together
	$newPass = $randval . $password;
	$encryptPass = hash('sha512', $newPass);
	
	//echo results
	echo "<p><strong>Password:</strong> " . $password . "</p>";
	echo "<p><strong>Salt + Password:</strong> " . $newPass . "</p>";
	echo "<p><strong>Hashed:</strong> " . $encryptPass . "</p>";
?>

<hr>

<!--Static method-->
<h1>Static method, with site wide salts</h1>
<p>Wordpress does it like this</p>
<?php
$PASSWORD = 'admin';
$P_SALT = 'qWI-lXh.5V##cx$:)|%]ScM3fk87Iuf-S^Ula>%Qv:jv^mm|O,T_=7I/`)~c@£e=';
$S_SALT = 'B~YUL1(NJ#eM9~Ip)|zx_uS/Mm^4PJ+tU+rs}Fd1or][1AbyY>RX2H(:,I~C@';

$mergedPass = $PASSWORD . $P_SALT . $S_SALT;

$encryptPass = hash('sha512', $mergedPass);

echo "<p><strong>Password + Salts:</strong> " . $mergedPass . "</p>";
echo "<p><strong>Hashed:</strong> " . $encryptPass . "</p>";
?>


</body>
</html>