<?php

$data = array_reverse($data, TRUE);

$scriptTemp = "";

$body .= "
    <script type=\"text/javascript\" src=\"https://www.google.com/jsapi\"></script>
    <script type=\"text/javascript\">
      google.load(\"visualization\", \"1\", {packages:[\"corechart\"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Date');
        data.addColumn('number', 'Lightness');
        data.addColumn('number', 'Temperature/C');
        data.addColumn('number', 'Humidity/%');
";

if (@$showAdditional)
{
	$body .= "
        data.addColumn('number', 'Dew point/C');
        data.addColumn('number', 'Saturation vapour pressure');
        data.addColumn('number', 'Actual vapour pressure');
	";		
}

$timeOffset = -120; // Offset between server handling cronjob, and server containg fecthdata.php

$i = 0;
$previousTime = 2000000000;

// Goes through the measurements
foreach ($data as $item => $results)
{
	/*
	This checks whether too much time has been passed since previous datapoints. If true, prints out empty datapoints.
	Too much passed time means missing data, which can be caused by
	- Module is offline
	- IOBridge service is offline
	- Server containing fetchdata.php is offline
	- Database or database server is offline
	- Cronjob is malfunctioning
	- Network problems between servers
	*/
	
	while ($results['module']['_unixtime'] > ($previousTime + 15*60)) // cron timestep * 1.5
	{
		$previousTime = ($previousTime + 10*60); // cron timestep
//		$date = strftime("%d.%m. %H.%M", ($results['module']['_unixtime'] + $timeOffset));
		$date = strftime("%d.%m. %H.%M", ($previousTime + $timeOffset));
		$scriptTemp .= "data.setValue($i, 0, '" . $date . "'); // $previousTime\n";
		$i++;
	}

	// Time
	$date = strftime("%d.%m. %H.%M", ($results['module']['_unixtime'] + $timeOffset));
	$scriptTemp .= "data.setValue($i, 0, '" . $date . "');\n";
	
	// Light
	$light = round($results['module']['channels'][2]['AnalogInput'] / 30, 1);
	$scriptTemp .= "data.setValue($i, 1, " . $light . ");\n";
	
	// Temperature
	$scriptTemp .= "data.setValue($i, 2, " . $results['module']['channels'][0]['AnalogInput'] . ");\n";
	
	// Humidity
	$scriptTemp .= "data.setValue($i, 3, " . $results['module']['channels'][1]['AnalogInput'] . ");\n";
	

	if (@$showAdditional)
	{
		// http://www.gorhamschaffler.com/humidity_formulas.htm
		//saturation vapor pressure(Es)
		$Es = 6.11 * pow(10.0, (7.5 * $results['module']['channels'][0]['AnalogInput'] / (237.7 + $results['module']['channels'][0]['AnalogInput'])));
		// actual vapor pressure(E) 
		$E = ($results['module']['channels'][1]['AnalogInput'] * $Es) / 100;
		//dewpoint temperature
		$Tdc = (-430.22 + 237.7 * log($E)) / (-log($E) + 19.08);
		$scriptTemp .= "data.setValue($i, 4, " . round($Tdc, 1) . ");\n";
	
		$scriptTemp .= "data.setValue($i, 5, " . round($Es, 1) . ");\n";
		$scriptTemp .= "data.setValue($i, 6, " . round($E, 1) . ");\n";
	}
	
	$previousTime = $results['module']['_unixtime'];
	$i++;
}

$body .= "        data.addRows($i);\n";
$body .= $scriptTemp;

$body .= "
        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, {pointSize: 0.5, width: $width, height: $height, chartArea: {width: " . ($width - 100) . ", height: " . ($height - 150) . ", left: '100', top: 'auto'}, legend: 'bottom', title: '$chartTitle " . $measurements . " measurements', series: {0:{color: '#D8EAFF', lineWidth: 10}, 1:{color: '#DC3912'}, 2:{color: '#3366CC'}}});
      }
    </script>
";

$body .= "<div id=\"chart_div\"></div>";



?>