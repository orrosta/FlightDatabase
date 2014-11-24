<!DOCTYPE html>

<html lang="en">
<head>
	<!-- Meta Data -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CIS562 Flights</title>

    <!-- CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/cloud.css" rel="stylesheet">
    <link href="css/jquery.nouislider.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=La+Belle+Aurore|Smythe' rel='stylesheet' type='text/css'>
</head>

<body>

    <!-- Connect to Database -->
    <?php include 'php/config.php'; ?>
    
    <!-- Navigation Bar -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
			<ul class="nav navbar-nav">
				<li>
					<a href="index.html">Flights</a>
				</li>
				<li>
					<a href="about.html">About</a>
				</li>
				<li>
					<a href="tools.html">Tools</a>
				</li>
			</ul>
    </div>
    </nav>
    
    <!-- Decorative Cloud Background -->
    <div id="clouds">
	       <div class="cloud x1"></div>
	       <div class="cloud x2"></div>
	       <div class="cloud x3"></div>
	       <div class="cloud x4"></div>
	       <div class="cloud x5"></div>               

    <!-- Page Content -->
    <div class="container" style="position:absolute;top:-50px;margin-left:15%;width:70%">
	
        <!-- Jumbotron Header -->
        <header class="jumbotron">
            <h1 style="font-family:'Smythe', cursive;">Search Flights</h1>
			
			<!-- Input Form -->
			<form class="form-horizontal" role="form" method="post">
        <div class="input-group input-group-sm">
          <span class="input-group-addon">Date</span>
          <input type="date" class="form-control" name="date" id="date" value="2014-12-12">
          <span class="input-group-addon"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
        </div><br>
				<div class="input-group input-group-sm">
          <span class="input-group-addon">Earliest Time</span>
						<select class="form-control" name="time1" id="time1">
							<option>00:00</option>
							<option>01:00</option>
							<option>02:00</option>
							<option>03:00</option>
							<option>04:00</option>
							<option>05:00</option>
							<option>06:00</option>
							<option>07:00</option>
							<option>08:00</option>
							<option>09:00</option>
							<option>10:00</option>
							<option>11:00</option>
							<option>12:00</option>
							<option>13:00</option>
							<option>14:00</option>
							<option>15:00</option>
							<option>16:00</option>
							<option>17:00</option>
							<option>18:00</option>
							<option>19:00</option>
							<option>20:00</option>
							<option>21:00</option>
							<option>22:00</option>
							<option>23:00</option>
						</select>
           <span class="input-group-addon"><span class="glyphicon glyphicon-time" aria-hidden="true"></span></span>
				</div><br>
				<div class="input-group input-group-sm">
          <span class="input-group-addon">Latest Flight</span>
					<select class="form-control" name="time2" id="time2">
							<option>00:00</option>
							<option>01:00</option>
							<option>02:00</option>
							<option>03:00</option>
							<option>04:00</option>
							<option>05:00</option>
							<option>06:00</option>
							<option>07:00</option>
							<option>08:00</option>
							<option>09:00</option>
							<option>10:00</option>
							<option>11:00</option>
							<option>12:00</option>
							<option>13:00</option>
							<option>14:00</option>
							<option>15:00</option>
							<option>16:00</option>
							<option>17:00</option>
							<option>18:00</option>
							<option>19:00</option>
							<option>20:00</option>
							<option>21:00</option>
							<option>22:00</option>
							<option>23:00</option>
          </select>
				  <span class="input-group-addon"><span class="glyphicon glyphicon-time" aria-hidden="true"></span></span>
				</div><br>
        <div class="input-group input-group-sm">
          <span class="input-group-addon">Connections</span>
				  <select class="form-control" name="conn" id="conn">
						<option>0</option>
						<option>1</option>
						<option>2</option>
						<option>3</option>
          </select>
				  <span class="input-group-addon"><span class="glyphicon glyphicon-resize-small" aria-hidden="true"></span></span>
				</div><br>
        <div class="input-group input-group-sm">
          <span class="input-group-addon">Departure</span>
				  <select class="form-control" name="icao1" id="icao1">
						<?php
		            $sql="SELECT DISTINCT departure FROM flights ORDER BY departure";
								$query = mysqli_query($db, $sql);
								while ($row=mysqli_fetch_assoc($query)) {
								    echo "<option value='".$row['departure']."'>".$row['departure']."</option>";
                }
						?>
          </select>
          <span class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></span>
				</div><br>
				<div class="input-group input-group-sm">
          <span class="input-group-addon">Departure</span>
				  <select class="form-control" name="icao2" id="icao2">
							<?php
								$sql="SELECT DISTINCT arrival FROM flights ORDER BY arrival";
								$query = mysqli_query($db, $sql);
								while ($row=mysqli_fetch_assoc($query)) {
                   echo "<option value='".$row['arrival']."'>".$row['arrival']."</option>";                                    
								}
							?>
						</select>
          <span class="input-group-addon"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></span>
				</div><br>
				<div class="form-group">
					<div class="col-sm-10">
						<button id="search" type="submit" class="btn btn-default">
							<span class="glyphicon glyphicon-plane" aria-hidden="true"></span>   Search Flights
						</button>
					</div>
				</div>
			</form>
        </header>
        
        <!-- Search Results -->
        <div class="panel panel-default">
          <div id="searchResult" class="panel-body">
             <!-- Search sesults appear here. -->
          </div>
        </div>
		
        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Created by S. Ali, J. Bostrom, M. Cox, and C. Wiehl 2014</p>
                </div>
            </div>
        </footer>
    </div>
    </div>
    <!-- /.container -->
    
    <!-- Javascript and jQuery -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.nouislider.all.js"></script>
    
    <!-- Ajax Button Handler -->
    <script>
         $(document).ready(function(){
              $("#search").click(function(e){
                   e.preventDefault();
                   var formData = $("form").serialize();
                   $.ajax({url:"php/search.php",type:"POST",data:formData,success:function(result) {
                        $("#searchResult").html(result);
                   }});
              });
         });
    </script>

</body>
</html>
