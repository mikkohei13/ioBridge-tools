<?php
/*
IOBridge tools for reading and displaying data
Mikko Heikkinen
https://github.com/mikkohei13
*/

$body = "";
$script = "";

// -------------------------------------------------------------------------------------------
// Content

$main = TRUE;
$title = "IOBridge";

$body .= "
<h1>IOBridge</h1>
	
<ul>
<li><a href=\"latest.php\">Latest</a></li>
<li><a href=\"chart.php?measurements=144\">Chart with 144 datapoints</a> &#8226; <a href=\"chart.php?measurements=300\">300</a> &#8226; <a href=\"chart.php?measurements=600\">600</a></li>

<li class=\"gaptop\">Data
<ul>
<li><a href=\"table.php\">Table</a></li>
<li><a href=\"csv.php\">CSV</a></li>
</ul>
</li>
<li class=\"gaptop\"><a href=\"fetchdata.php?method=test\">Fetch data (method: test)</a></li>
</ul>
";


// -------------------------------------------------------------------------------------------
// Page

require_once "include/header.php";
echo $body;
require_once "include/footer.php";

?>

