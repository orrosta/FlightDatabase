<!DOCTYPE html>
<html lang="en">

	<head>

	</head>

	<body>
	
	<!-- PHP Code to access Database -->
		<p> This is a test.</p>
	
		<?php

			function getTimeStamp($time, $month, $day, $year)
			{
				$time = $day + "/" + $month + "/" + $year + ":" + $time;
				return strtotime(time);
			}

			function getArrivalTime($startTimeStamp, $durationMinutes)
			{
				return $startTimeStamp + ($durationMinutes * 60);
			}


			$servername = "mysql.cis.ksu.edu";
			$username = "cis562_flights";
			$password = "b3stP4SSW0RD3v3r";
			$dbname = "cis562_flights";

			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);
			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			} 

			$sql = "SELECT AirlineID, Name, ICAO, Country FROM airlines LIMIT 25";



			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				// output data of each row
				while($row = $result->fetch_assoc()) {
					echo "ID: " . $row["AirlineID"]. " - Name: " . $row["Name"]. " - ICAO: " . $row["ICAO"]. " - Country: " . $row["Country"]."<br>";
				}
			} else {
				echo "0 results";
			}
			$conn->close();




		?>

	</body>

</html>
