<?
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>


	<link rel="stylesheet" type="text/css" media="screen" href="../../lib/jquery/themes/redmond/jquery-ui-1.8.2.custom.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="../../lib/Jquery/css/ui.jqgrid.css" />

	<script src="../../lib/jquery/js/jquery-1.5.2.min.js" type="text/javascript"></script>
	<script src="../../lib/jquery/js/i18n/grid.locale-en.js" type="text/javascript"></script>
	<script src="../../lib/jquery/js/jquery.jqGrid.min.js" type="text/javascript"></script>
	<script src="facturacion.js" type="text/javascript"></script>
	
</head>
	
	<?php 
	
		echo $_SESSION['usrname'];
		echo $_SESSION['efector'];
		echo $_SESSION['cuie'];   // cuieee
			
	?>

	<table id="gridpra"></table> 
    <a href="practicas2.php?cuie='H04407'">practicas2</a> 
	
</body>
</html>