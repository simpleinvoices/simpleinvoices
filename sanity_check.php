<?php

include('./config/config.php');

$display_block ="

<b>Sanity check of the invoices</b>
<br>
<br>
This feature is stilla work-in-progress, please refer to our homepage: <a href='http://simpleinvoices.sf.net'>http://simpleinvoices.sf.net</a> for update

";




?>
<html>
<head>

<?php include('./include/menu.php'); ?>
<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>


<title>Simple Invoices - Sanity check
</title>
</head>
<?php include('./config/config.php'); ?>
<body>
<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>

<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css">
<br>
<div id="container">

<div id="header"></div>
<?php echo $display_block; ?>
<div id="footer"></div>
</div>
</div>

</body>

