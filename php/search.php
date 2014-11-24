<?php
   include 'config.php';
   include 'functions.php';

   // Variables
   $date = $_POST["date"]; // Formatted as YYYY-MM-DD
   $weekday = date('N', strtotime($date)); // Formatted as X
   $time1 = $_POST["time1"]; // Formatted as XX:XX AM
   $time2 = $_POST["time2"]; // Formatted as XX:XX AM
   $dateS = date_create_from_format($format = 'Y-m-d H:i', $date." ".$time1); // Starting DateTime
   $dateE = date_create_from_format($format = 'Y-m-d H:i', $date." ".$time2); // Ending DateTime
   $conn = $_POST["conn"]; // Formatted as X
   $icao1 = $_POST["icao1"]; // Formatted as AAA
   $icao2 = $_POST["icao2"]; // Formatted as AAA
   $result = ""; // MySQLi query result
    
    // Query for non-stop flights
		if (intval($conn) == 0) {
       $sql = "SELECT f.departure, f.arrival, f.dep_time, l.Name as airline, f.airline as iata, f.flightnum, f.duration, a.Name as a_name, a.City as a_city, a.Country as a_country, a.Tz as a_tz, b.Name as b_name, b.City as b_city, b.Country b_country, b.Tz as b_tz
					FROM flights f, airlines l, airports a, airports b
          WHERE ('".$icao1."' = f.departure) 
          AND ('".$icao2."' = f.arrival)
          AND (f.departure = a.IATA) 
          AND (f.arrival = b.IATA) 
          AND (f.day_op LIKE '%".$weekday."%')
          AND (CAST(f.dep_time as time) BETWEEN '".$time1."' AND '".$time2."')
          AND (f.airline = l.IATA)
          LIMIT 25";
        $result = $db->query($sql);
		}

    // If the query returns anything, show it
		if ($result->num_rows > 0) {
			$i = 1;
			while($row = $result->fetch_assoc()) {
        $arrivalTime = dtConvert($date, $row["dep_time"], $row["a_tz"], $row["b_tz"], $row["duration"]);
				echo "<table class=\"table table-bordered table-striped\">
                 <tr>
                     <th>Itinerary</th>
                     <th>Carrier</th>
                     <th>Departure</th>
                     <th>Arrival</th>
                     <th>Duration</th>
                 </tr>
                 <tr>
                     <td>".$i++."</td>
                     <td><img src=\"images/airlines/".$row["iata"].".png\" alt=\"".$row["iata"]."\"><br>
                         ".$row["airline"]."<br>
                         Flight ".$row["flightnum"]."</td>
                     <td>Date/Time: ".$date." ".$row["dep_time"]."<br>
                         <small>".$row["a_tz"]." Timezone</small>"."<br>
                         ".$row["a_name"]." Airport (".$row["departure"].")"."<br>
                         <small>".$row["a_city"].", ".$row["a_country"]."</small></td>
                     <td>Date/Time: ".$arrivalTime."<br>
                         <small>".$row["b_tz"]." Timezone</small><br>
                         ".$row["b_name"]." Airport (".$row["arrival"].")"."<br>
                         <small>".$row["b_city"].", ".$row["b_country"]."</small></td>
                     <td>In Flight: ".toS($row["duration"])."</br>
                         Layover:</td>
                 </tr>
             </table>";
			}
		} else {
			echo "No flights found.";
		}
  $db->close();
?>