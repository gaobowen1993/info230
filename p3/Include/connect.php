<?php		
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if ( $mysqli->connect_error ) {
		die("Failed to connect to MySQL: ".$mysqli->connect_error);
	}
?>