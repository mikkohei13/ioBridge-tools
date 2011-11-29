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

$title = "IOBridge table";


$body .= "<table>";
$body .= "
<tr>
	<th>Datetime</th>
	<th>Temperature/C</th>
	<th>Humidity/%</th>
	<th>Light</th>
</tr>";
foreach ($data as $item => $results)
{
	$body .= "
	<tr>
		<td>" . $results['module']['local_time'] . "</td>
		<td>" . $results['module']['channels'][0]['AnalogInput'] . "</td>
		<td>" . $results['module']['channels'][1]['AnalogInput'] . "</td>
		<td>" . $results['module']['channels'][2]['AnalogInput'] . "</td>
	</tr>";
}
$body .= "</table>";

// -------------------------------------------------------------------------------------------
// Page

require_once "include/header.php";
echo $body;
require_once "include/footer.php";

?>

