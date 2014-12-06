<?php
   include 'config.php';
   include 'functions.php';

   // Variables
   $date = $_POST["date"]; // Formatted as YYYY-MM-DD
   $weekday = date('N', strtotime($date)); // Formatted as X
   $time1 = $_POST["time1"]; // Formatted as XX:XX AM
   $time2 = $_POST["time2"]; // Formatted as XX:XX AM
   $dateS = $date." ".$time1.":00";
   $dateE = $date." ".$time2.":00";
   $dateS = "\"".$dateS."\"";
   $dateE = "\"".$dateE."\"";
   $conn = $_POST["conn"]; // Formatted as X
   $icao1 = $_POST["icao1"]; // Formatted as AAA
   $icao2 = $_POST["icao2"]; // Formatted as AAA
  
   if($_SERVER['REQUEST_METHOD']=='POST'){
      $query = getRout($icao1, $icao2, $dateS, $dateE, $weekday, $conn);
      $result = $db -> query($query);
      displayRoutes($result, $date);
   }

      function getRout($departure, $destination, $min_dep_time, $max_dep_time, $weekday, $conn)
   {

      //cho  "(".multiFlight($departure, $destination, $min_dep_time, $max_dep_time, $weekday, $conn).") UNION (".nonstop($departure, $destination, $min_dep_time, $max_dep_time, $weekday).") Order by duration Limit 25";
      if(intval($conn) == 0)
        return nonstop($departure, $destination, $min_dep_time, $max_dep_time, $weekday);
      else
        return "(".multiFlight($departure, $destination, $min_dep_time, $max_dep_time, $weekday, $conn).") UNION (".nonstop($departure, $destination, $min_dep_time, $max_dep_time, $weekday).") Order by duration Limit 25";
   }


   // Query for non-stop flights
   function nonStop($departure, $destination, $min_dep_time, $max_dep_time, $weekday)
   {
      global $db;
      $sql = "SELECT 
        f.departure,
        f.arrival,
        f.dep_time,
        f.airline as iata,
        f.flightnum,
        null as airline1,
        a.Name as a_name, 
        a.City as a_city,
        a.Country as a_country,
        a.Tz as a_tz,
        f.duration as duration1,
        null as layover1,
        null as flightnum2,
        null as duration2,
        null as arrival2,
        cast(f.duration as unsigned) as duration,
        null as dep2,
        null as iata2,
        null as in_flight_duration,
        null as airline2,
        b.Name as b_name,
        b.City as b_city, 
        b.Country b_country,
        b.Tz as b_tz,
        l.Name as airline 


      FROM flights f, airlines l, airports a, airports b
      WHERE ('".$departure."' = f.departure) 
      AND ('".$destination."' = f.arrival)
      AND (f.departure = a.IATA) 
      AND (f.arrival = b.IATA) 
      AND (f.day_op LIKE '%".$weekday."%')
      AND (f.airline = l.IATA)
      ORDER BY duration LIMIT 25";
      return $sql;
      //return $db -> query($sql);
   }

   function multiFlight($departure, $destination, $min_dep_time, $max_dep_time, $weekday, $conn)
   {
     global $db;

    $sql = "SELECT 
    f.departure, 
    f.arrival,
    f.dep_time,
    f.airline as iata,
    f.flightnum,
    f.Name as airline1,
    f.a_name as a_name,
    f.City as a_city,  
    f.Country as a_country, 
    f.Tz as a_tz,
    f.duration as duration1,
    timediff(cast(f2.adjusted_dep_time as time), ADDTIME(cast(f.adjusted_dep_time as time), sec_to_time(f.duration * 60))) as layover1,
    f2.flightnum as flightnum2,
    f2.duration as duration2, 
    f2.arrival as arrival2,
    time_to_sec(timediff(ADDTIME(cast(f2.adjusted_dep_time as time),sec_to_time(f2.duration * 60)), cast(f.adjusted_dep_time as time))) / 60 as duration,
    f2.dep_time as dep2, 
    f2.airline as iata2, 
    (f.duration + f2.duration) as in_flight_duration,  
    f2.Name as airline2,
    f2.a_name as b_name,
    f2.City as b_city,
    f2.Country as b_country,
    f2.TZ as b_tz,
    null as airline

    FROM (SELECT departure, 
              arrival, 
              dep_time,
              ADDTIME(cast(dep_time as time),sec_to_time(a.timezone * 3600)) as adjusted_dep_time,
              airline,
              flightnum,
              duration,
              l.Name as Name,
              a.Name as a_name,
              a.City as City,
              a.Country as Country,
              a.Tz as Tz  
          from flights, airlines l, airports a 
          WHERE departure = '".$departure."' 
              AND day_op LIKE '%".$weekday."%' 
              AND l.IATA = airline 
              AND departure = a.IATA 
              AND cast(dep_time as time) >  time($min_dep_time)
              AND cast(dep_time as time) < time($max_dep_time)) f 

    JOIN (SELECT departure,
          arrival,
          dep_time,
          ADDTIME(cast(dep_time as time),sec_to_time(a.timezone * 3600)) as adjusted_dep_time,
          airline,
          flightnum,
          duration,
          l.Name as Name,
          a.Name as a_name,
          a.City as City,
          a.Country as Country,
          a.Tz as Tz       
          FROM flights, airlines l, airports a 
          WHERE arrival = '".$destination."'
              AND day_op LIKE '%".$weekday."%'
              AND l.IATA = airline
              AND arrival = a.IATA)f2

    ON f2.departure = f.arrival
      AND cast(f2.adjusted_dep_time as time) > ADDTIME(cast(f.adjusted_dep_time as time),sec_to_time(f.duration * 60))
      AND time_to_sec(timediff(cast(f2.adjusted_dep_time as time), ADDTIME(cast(f.adjusted_dep_time as time), sec_to_time(f.duration * 60)))) / 60 > 39
         
    order by duration Limit 25";
     return $sql;      
   }

   function displayRoutes($result, $date){
    if ($result->num_rows > 0) {
      $i = 1;
      while($row = $result->fetch_assoc()) {
        $arrivalTime = dtConvert($date, $row["dep_time"], $row["a_tz"], $row["b_tz"], intval($row["duration"]));
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
   }

  $db->close();
?>