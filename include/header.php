<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title><?php echo $title; ?></title>
	<?php echo $script; ?>
	
	<link href="include/style.css" rel="stylesheet" />
	<style type="text/css">
	<?php echo $styles; ?>
	</style>
</head>
<body>
<?php
if (@$main != TRUE)
{
	echo "<p id=\"back\"><a href=\"./\">&laquo; Back to menu</a></p>";
}
?>