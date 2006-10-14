<?php

include('./config/config.php');


$fp = fopen( "ReadMe.html", "r" );
if(!$fp)
{
    echo "Couldn't open the data file. Try again later.";
    exit;
}
$filename ="ReadMe.html";
$display_block = fread( $fp, filesize( $filename ) );
?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php include('./include/menu.php'); ?>
<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>


<title>Simple Invoices - Instructions</title>
<?php include('./config/config.php'); ?>
<body>
<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>

<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css">
<br>
<div id="container">
<div id=header></div>
<?php echo $display_block; ?>
<div id="footer"></div>
</div>
</div>
<?php fclose( $fp ); ?>
</body>

