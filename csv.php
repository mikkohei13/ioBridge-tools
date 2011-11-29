<?php
/*
IOBridge tools for reading and displaying data
Mikko Heikkinen
https://github.com/mikkohei13
*/


$body = "";
$script = "";

require_once "include/database.php";

// -------------------------------------------------------------------------------------------
// Database

$database = New DatabaseIO();
$data = $database->returnArray();

//print_r ($data); // debug

// -------------------------------------------------------------------------------------------
// Content

$title = "IOBridge CSV";

$body .= "<pre>";
$body .= "i	status	location	method	unixtime	datetime	temperature/C	humidity/%	light/RAW\n";


$i = 0;
foreach ($data as $item => $results)
{
	$ch1 = str_replace(".", ",", $results['module']['channels'][0]['AnalogInput']);
	$ch2 = str_replace(".", ",", $results['module']['channels'][1]['AnalogInput']);
	$ch3 = str_replace(".", ",", $results['module']['channels'][2]['AnalogInput']);
	$body .= $i . "\t" . @$results['module']['status'] . "\t" . @$results['module']['location'] . "\t" . @$results['module']['_method'] . "\t" . @$results['module']['_unixtime'] . "\t" . $results['module']['local_time'] . "\t" . $ch1 . "\t" . $ch2 . "\t" . $ch3 . "\n";
	$i++;
}

$body .= "</pre>";

// -------------------------------------------------------------------------------------------
// Page

require_once "include/header.php";
echo $body;
require_once "include/footer.php";

?>

