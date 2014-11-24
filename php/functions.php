<?php
    // FUNCTIONS -------------------------------------------------------//
   
		// Convert Minutes X into X Hours and X Minutes
		// @t is the String time in minutes
    // @f is the String format to output it as
    // @return a String
		function toS($t, $f = '%dh %dm') {
        if (intval($t) < 1) {
				    return;
			  }
			  $h = floor($t/60);
			  $m = $t%60;
			  return sprintf($f, $h, $m);
		}
   
		// Convert military Hours and Minutes XX:XX into Minutes X
		// @s is the STRING standard time as XX:XX
    // @return an Integer
		function toM($s) { 
        $t = explode(":", $s); 
        $h = settype($t[0], integer);
        $m = settype($t[1], integer);
        $mm = ($h * 60) + $m;
        return $mm;
		}
   
   // Find the arrival time of a flight
   // @d is the Date to convert YYYY-MM-DD
   // @t is the time to convert XX:XX
   // @tzA is the original DateTimezone (ex: "Pacific/Port_Moresby")
   // @tzB is the DateTimezone to convert to (ex: "Pacific/Port_Moresby")
   // @m is the minutes of a flight duration as an Integer
   // @return a String YYYY-MM-DD HH:II:SS
   function dtConvert($d, $t, $tzA, $tzB, $m) {
        $d2 = new DateTime($d." ".$t, new DateTimeZone($tzA));
        $d2->add(new DateInterval('PT'.$m.'M'));
        $d2->setTimeZone(new DateTimeZone($tzB));
        return $d2->format('Y-m-d H:i');
   }
   
   // Add M minutes to the current DateTime.
   // @date is the DateTime to add to
   // @m are the minutes as an Integer
   // @return a DateTime
   function dtAddMinutes($date, $m) {
        $interval = new DateInterval('P'.$m.'M');
        $newDate = date_add($date, $interval);
        return $newDate;
   }
?>