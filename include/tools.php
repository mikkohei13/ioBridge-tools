<?php

function seconds2minutesandseconds($seconds)
{
	$mins = floor ($seconds / 60);
    $secs = $seconds % 60;
	
	if (0 == $mins)	
	{
		$ret = "$secs seconds";
	}
	else
	{
		$ret = "$mins minutes and $secs seconds";
	}
	return $ret;
}


function getIntegerGET($name, $default, $max)
{
	if (@is_numeric($_GET[$name]) && @$_GET[$name] == (int) @$_GET[$name])
	{
		if ($_GET[$name] > $max)
		{
			$measurements = $max;
		}
		elseif ($_GET[$name] < 1)
		{
			$measurements = 1;
		}
		else
		{
			$measurements = $_GET[$name];
		}
	}
	else
	{
		$measurements = $default; 
	}


	return $measurements;
}




?>