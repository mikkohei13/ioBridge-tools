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
// Setup & GET-variables

$maximumMesurements = 1000;
$defaultMesurements = 432; // 432 = 3 days with 10 minute interval

$measurements = getIntegerGET("measurements", $defaultMesurements, $maximumMesurements);
$skip = getIntegerGET("skip", 0, 100000);

if (isset($_GET['add']))
{
	$showAdditional = TRUE;
}
else
{
	$showAdditional = FALSE;
}

// -------------------------------------------------------------------------------------------
// Database

$database = New DatabaseIO();
$data = $database->returnSelected($measurements, "cron10", "DESC", $skip);

// -------------------------------------------------------------------------------------------
// Content

$styles = "
#chart_div {
	border: 1px solid #ccc;
}
";

$title = "IOBridge chart";

$width = 1200;
$height = 600;
$chartTitle = " ";

require_once "include/chart.php";

// -------------------------------------------------------------------------------------------
// Page

require_once "include/header.php";
echo $body;
require_once "include/footer.php";

?>

