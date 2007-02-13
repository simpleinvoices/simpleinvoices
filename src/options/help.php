<?php
include('./include/include_main.php');

$display_block ="

<b>Help</b>
 <hr></hr>
       <div id=\"left\">
<br>
For information regarding the setup,installation, and use of Simple Invoices please refer to the Instructions sub-menu in the Option menu. <br>
<br>For other queries please refer to the Simple Invoices website <a href='http://www.simpleinvoices.org'>http://www.simpleinvoices.org</a> and the Simple Invoices forum at <a href='http://www.simpleinvoices.org/forum'>http://www.simpleinvoices.org/forum</a>

";




?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include('./config/config.php'); ?>
<body>
<?php 
	echo $display_block; 
?>
</div>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
