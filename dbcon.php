<?php
	// variables na mag hahandle ng mga data na need natin
	$hostname = "localhost";
	$username = "root";
	$password = "";
	$dbname = "ajax_db";

	// variable na maghahandle ng database connection
	$conn = new mysqli($hostname, $username, $password, $dbname);

	// if condition to check if the connection is successfull
	if ($conn) {
		// echo "Connected Successfully!";
	}else{
		echo "Connection Error: ".$conn->error;
	}
?>