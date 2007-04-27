<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

include('./config/config.php');

$display_block ="

<b>Sanity check of the invoices</b>
 <hr></hr>
       <div id=\"left\">
<br>
This feature is still a work-in-progress, please refer to our homepage: <a href='http://www.simpleinvoices.org'>http://www.simpleinvoices.org</a> for update.

";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<?php include('./config/config.php'); ?>
<body>
<?php 
	echo $display_block;
?>
</div>