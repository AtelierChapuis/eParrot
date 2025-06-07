<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<!-- Original project by Lars Lindehaven, updated by Mark Chapuis -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="refresh" content="3"> <!-- MC: dirty. setup an autoupdate of the chart.js instead of just refreshing the whole webpage-->
	<meta name="description" content="Chapuis Atelier Sensors">

	<title>Chapuis Atelier Sensors Dashboard</title>
	
	<link rel="stylesheet" href="DCstyles.css">

<style>
.center {
	margin-left: auto;
	margin-right: auto;
}

</style>
</head>

<body>
	
<!-- PHP Connect and retrieve values from DB -->
<?php
	// Define the SQL constants and variables
	define("DECIMALS", 2);
	$sensor_array = array("28-0416850e9aff"=>"TEMP");
	$servername = "localhost";
	$username = "pi";
	$password = "1q2w3e4r5t";
	$dbname2 = "web";

	// Get the date and time of the latest sensor reading
	$day = $_GET["d"];
	$month = $_GET["m"];
	$year = $_GET["y"];
	if (!empty($day)) {
		$sqldate = "sampled_at BETWEEN '" . $day . " 00:00:00' AND '" . $day . " 23:59:59'";
		$period = $day;
	} else if (!empty($month)) {
		$sqldate = "sampled_at BETWEEN '" . $month . "-01 00:00:00' AND '" . $month . "-31 23:59:59'";
		$period = $month;
	} else if (!empty($year)) {
		$sqldate = "sampled_at BETWEEN '" . $year . "-01-01 00:00:00' AND '" . $year . "-12-31 23:59:59'";
		$period = $year;
	} else {
		$sqldate = "DATE(`sampled_at`) = CURDATE();";
		$period = date('Y-m-d');
	}

	// Create the connection to the DB
	$conn2 = new mysqli($servername, $username, $password, $dbname2);
	if ($conn2->connect_error) {
		echo "<table><tr><td>N/A</td></tr></table>";
		die();
	}

	// Populate the values for the date, from the SQL DB  into local variables
	$numSensors = 0;
	foreach ($sensor_array as $sensor_id=>$sensor_name) {
		$value = "N/A";
		$numSensors++;
		
		$sql2 = "SELECT sampled_at, sensor_val, abv_val FROM temperature WHERE sensor_id = '" . $sensor_id . "' AND " . $sqldate;
		$result2 = $conn2->query($sql2);

		// Assign the queried values to PHP variables
		if ($result2->num_rows > 0) {
			while ($row = $result2->fetch_assoc()) {
				$at = $row["sampled_at"];
				$value = $row["sensor_val"];
				$abvValue = $row["abv_val"];
				if ($value > -273.16) {
					$value = number_format($value, DECIMALS);
				}
			}
		}
	}
?>
<!-- /PHP -->

<!-- Top of page section-->
<div>
	<img src="/images/dc-logo.png" alt="Chapuis Atelier" width="50"> &#169; Chapuis Atelier
</div>

<hr align="left", width=90%-->
<center><tt>Data</tt></center>

<!-- Creating a table within a table, so that I get three sensors values next to each other -->

<table class="center">
	<thead>
		<tr>
			<th colspan = "8">Number of sensors connected: <tt><?php echo $numSensors ?></tt></th>	
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><!-- First colmn of the big table-->
				<table class="GeneratedTable">
				  <thead>
					<tr>
					  <th colspan = "2" style="background-color:#32a852;">Sensor #1</th>
					</tr>
				  </thead>
				  <tbody>
					<tr>
					  <td>Temperature:</td>
					  <td><tt><?php echo $value; ?><sup>&deg;C</sup></tt></td>
					</tr>
					<tr>
					  <td>ABV of distillate:</td>
					  <td><tt><?php echo $abvValue ?> <sup>%</sup></td>
					</tr>
					<tr>
					  <td>ABV in the pot:</td>
					  <td><tt>-TBD-</tt></td>
					</tr>
					<tr>
					  <td>Sensor ID#:</td>
					  <td><tt><?php echo $sensor_id ?></td>
					</tr>
					<tr>
					  <td>Sensor type:</td>
					  <td><tt><?php echo $sensor_name ?></td>
					</tr>
					<tr>
					  <td>Time of last reading:</td>
					  <td><tt><?php echo $at ?></td>
					</tr>    
				  </tbody>
				</table>
			</td><!-- End first column -->
			<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td>
				<table class="GeneratedTable">
				  <thead>
					<tr>
					  <th colspan = "2" style="background-color:#0000FF;">Sensor #2</th>
					</tr>
				  </thead>
				  <tbody>
					<tr>
					  <td>Temperature:</td>
					  <td><tt><?php echo $value; ?><sup>&deg;C</sup></tt></td>
					</tr>
					<tr>
					  <td>ABV of distillate:</td>
					  <td><tt><?php echo $abvValue ?> <sup>%</sup></td>
					</tr>
					<tr>
					  <td>ABV in the pot:</td>
					  <td><tt>-TBD-</tt></td>
					</tr>
					<tr>
					  <td>Sensor ID#:</td>
					  <td><tt><?php echo $sensor_id ?></td>
					</tr>
					<tr>
					  <td>Sensor type:</td>
					  <td><tt><?php echo $sensor_name ?></td>
					</tr>
					<tr>
					  <td>Time of last reading:</td>
					  <td><tt><?php echo $at ?></td>
					</tr>    
				  </tbody>
				</table>
			</td><!-- End 2nd column -->
			<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td>
				<table class="GeneratedTable">
				  <thead>
					<tr>
					  <th colspan = "2" style="background-color:#FF0000;">Sensor #3</th>
					</tr>
				  </thead>
				  <tbody>
					<tr>
					  <td>Temperature:</td>
					  <td><tt><?php echo $value; ?><sup>&deg;C</sup></tt></td>
					</tr>
					<tr>
					  <td>ABV of distillate:</td>
					  <td><tt><?php echo $abvValue ?> <sup>%</sup></td>
					</tr>
					<tr>
					  <td>ABV in the pot:</td>
					  <td><tt>-TBD-</tt></td>
					</tr>
					<tr>
					  <td>Sensor ID#:</td>
					  <td><tt><?php echo $sensor_id ?></td>
					</tr>
					<tr>
					  <td>Sensor type:</td>
					  <td><tt><?php echo $sensor_name ?></td>
					</tr>
					<tr>
					  <td>Time of last reading:</td>
					  <td><tt><?php echo $at ?></td>
					</tr>    
				  </tbody>
				</table>
			</td><!-- End 3rd column -->
		</tr>
	</tbody>
</table>

<hr align="left", width=90%>



<div id="chart-wrapper">
	<!-- Create the charts.js canvas. This is where the chart will be displayed on the webpage. -->
	<!-- All the parameters of the chart are defined in the SCRIPTS section of this webpage.-->
	<canvas id="sensor1Chart"></canvas>
</div>

<br>

<!--hr align="left", width=50%-->
<div>
	<?php
		$data4 = array();
		
		// Query SQL for the last 10 values of the selected variables 
		$sql3 = "SELECT CAST(sampled_at AS TIME) time, sensor_val, abv_val FROM temperature ORDER BY sampled_at DESC LIMIT 40";

		$result3 = $conn2->query($sql3); 

		// Create a table with last 10 readings that were queried.
		if ($result3->num_rows > 0) {
			while($row3 = mysqli_fetch_array($result3)) {
				$timeVal4[] = $row3["time"];
				$tempVal4[] = $row3["sensor_val"];
				$abvVal4[] = $row3["abv_val"];		
			}
		} 
		else {
			echo "0 results";
		}
	?>
</div>

<!--hr align="left", width=50%-->
<br>
&#169; Atelier Chapuis


<?php
	// Close the connection to the SQL DB
	$conn2->close();
?>


<!-- === JAVASCRIPTS SECTION START === -->

<!-- Call the charts.js latest version from their webpage -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<!-- Create the chart parameters-->
<script>
  // Get variables from the previous SQL query in PHP, so they're readable by javascript
  var timeVal = <?php echo json_encode($timeVal4) ?>;
  var tempVal = <?php echo json_encode($tempVal4) ?>;
  var abvVal = <?php echo json_encode($abvVal4) ?>;


  // Define the Data (Y-axis) for the chart
  const data = {
	labels: timeVal,
	datasets: [
	{
	  label: 'Temperature 1',
	  backgroundColor: '#32a852',
	  borderColor: '#32a852',
	  data: tempVal,
	},
	{
	  label: 'Derived ABV 1',
	  backgroundColor: '#8fffad',
	  borderColor: '#8fffad',
	  data: abvVal,		
	}]
  };

  // Define the configuration of the chart.
  const config = {
	type: 'line',
	data: data,
	options: {
		responsive: true,
		maintainAspectRatio: false,
		animation: {
			duration: 0
		},
		scales: {
			yAxes: [{
				ticks: {
					beginAtZero:true,
				}
			}]			
		},
		plugins: {
			title: {
				display: true,
				text: 'Sensors Data',
			}
		}
	}
  };
</script>

<!-- Render the chart-->
<script>
	const sensor1Chart = new Chart(document.getElementById('sensor1Chart'), config);
	setInterval(function(){sensor1Chart.update()},1000);
</script>

<!-- === SCRIPTS SECTION END === -->

</body>	
</html>

