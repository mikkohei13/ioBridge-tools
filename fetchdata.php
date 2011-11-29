<?php
/*
IOBridge tools for reading and displaying data
Mikko Heikkinen
https://github.com/mikkohei13
*/


require "../../secure/iobridge.php";
require_once "include/database.php";

$dataJSON = file_get_contents("http://www.iobridge.com/api/module/feed.json?key=$apikey");
$data = json_decode($dataJSON, TRUE);

// If Module is offline, only echo error message
if ("Offline" == $data['module']['status'])
{
	$date = date("Ymd His");
	echo "Module offline $date";
	exit;
}

// -------------------------------------------------------------------------------------------
// Database

$database = New DatabaseIO();

echo $database->formatAndInsert($data);

?>