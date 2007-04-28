<?php

include('./include/include_main.php');


$fp = fopen( "./modules/documentation/ChangeLog.html", "r" );
if(!$fp)
{
    echo "Couldn't open the data file. Try again later.";
    exit;
}
$filename ="./modules/documentation/ChangeLog.html";
$display_block = fread( $fp, filesize( $filename ) );
?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<body>
<b>Simple Invoices Change Log</b>
 <hr></hr>
       <div id="left">

<?php 
	echo $display_block;
	fclose( $fp ); 
?>
</div>