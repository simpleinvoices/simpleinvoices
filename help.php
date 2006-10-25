<?php
include('./include/include_main.php');

$display_block ="

<b>Help</b>
<br>
<br>
For information regarding the setup,installation, and use of Simple Invoices please refer to the Instructions sub-menu in the Option menu. <br>
<br>For other queries please refer to the Simple Invoices website <a href='http://www.simpleinvoices.org'>http://www.simpleinvoices.org</a> and the Simple Invoices forum at <a href='http://www.simpleinvoices.org/forum'>http://www.simpleinvoices.org/forum</a>

";




?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>


<title>Simple Invoices - Help
</title>
<?php include('./include/menu.php'); ?>
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

