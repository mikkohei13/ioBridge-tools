<?php
/*
IOBridge tools for reading and displaying data
Mikko Heikkinen
https://github.com/mikkohei13
*/


$body = "";
$script = "";
$styles = "";

require_once "include/database.php";
require_once "include/tools.php";

// -------------------------------------------------------------------------------------------
// Database

$database = New DatabaseIO();
$data = $database->returnSelected(1, "cron10");

//echo count($data);

// -------------------------------------------------------------------------------------------
// Content

$title = "IOBridge Latest";

$latest = array_shift($data);

// Measurements
$temp = $latest['module']['channels'][0]['AnalogInput'];
$humidity = $latest['module']['channels'][1]['AnalogInput'];

// Time
$timeDiff = time() - $latest['module']['_unixtime'];

// ----------------------------------

// http://www.gorhamschaffler.com/humidity_formulas.htm

//saturation vapor pressure(Es)
$Es = 6.11 * pow(10.0, (7.5 * $temp / (237.7 + $temp)));

// actual vapor pressure(E) 
$E = ($humidity * $Es) / 100;

//dewpoint temperature
$Tdc = (-430.22 + 237.7 * log($E)) / (-log($E) + 19.08);

// ----------------------------------

// http://forum.onlineconversion.com/showthread.php?t=567&highlight=humidity

//$humidity = $E / $Es;
//$Es = $humidity / $E;
//$E = $humidity / $Es;

/*
$P = 101.325; // kilopascal
$MR = 0.622 * $E / ($P - $E);
$MRg = $MR * 1000;

$T = $temp + 273.15; // kelvin

//$AH = $E * M / ($T * R); // g/m³ H2O.
*/

// http://forum.onlineconversion.com/showthread.php?t=567&highlight=humidity&page=2

$T = $temp + 273.15; // kelvin
$r = $humidity / 100;

$absoluteHumidity = 1320.65 / $T * $r * pow(10, (7.4475 * ($T - 273.14) / ($T - 39.44)));

// ----------------------------------

$body .= "<h1>Latest conditions</h1>";

$body .= "<div id=\"current\">";
$body .= "<p id=\"timediff\">Measurements " . seconds2minutesandseconds($timeDiff) . " ago</p>";

$body .= "<div id=\"items\">";
$body .= "<p id=\"item\"><span class=\"ititle\">Temperature:</span><span class=\"imeasurement\"><strong>" . $temp . "</strong> &deg;C</span></p>";
$body .= "<p id=\"item\"><span class=\"ititle\">Relative humidity:</span><span class=\"imeasurement\"><strong>" . $humidity . "</strong> %</span></p>";
$body .= "</div>";

$body .= "<div id=\"items2\">";
//$body .= "<p>Saturation vapour pressure: " . round($Es, 1) . " kPa ??</p>";
//$body .= "<p>Actual vapour pressure: " . round($E, 1) . " kPa ??</p>";
$body .= "<p>Dewpoint temperature: " . round($Tdc, 1) . " &deg;C</p>";
$body .= "<p>Absolute humidity: " . round($absoluteHumidity, 1) . " g/m<sup>3</sup></p>";
$body .= "</div>";

$body .= "<p id=\"clear\">&nbsp;</p>";

$body .= "</div>";

//$body .= "<p>Mixing ratio: " . round($MRg, 1) . " g H<sup>2</sup>O/kg dry air</p>";

// -------------------------------------------------------------------------------------------
// Chart

$measurements = 144;
$width = 800;
$height = 500;
$chartTitle = "Latest";

$data = $database->returnSelected($measurements, "cron10", "DESC", 0);

$body .= "<div id=\"chart\">";

require_once "include/chart.php";

$body .= "</div>";

$body .= "<p id=\"chartlinks\">Larger chart with  
<a href=\"chart.php?measurements=144\">144</a> &#8226; 
<a href=\"chart.php?measurements=300\">300</a> &#8226; 
<a href=\"chart.php?measurements=600\">600</a> &#8226; 
<a href=\"chart.php?measurements=1200\">1200</a> 
datapoints
</p>";


// -------------------------------------------------------------------------------------------
// Debug

//$body .= "<hr /><pre>" . print_r($latest, TRUE); // debug

// -------------------------------------------------------------------------------------------
// Page

require_once "include/header.php";
echo $body;
require_once "include/footer.php";

?>

