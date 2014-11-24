<?php
		// Database credentials
		$servername = "mysql.cis.ksu.edu";
		$username = "cis562_flights";
		$password = "b3stP4SSW0RD3v3r";
		$dbname = "cis562_flights";

		// Create connection
		$db = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($db->connect_error) {
       die("Connection failed: " . $db->connect_error);
		}
?>