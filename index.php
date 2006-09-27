<?php

include('./include/auth/auth.php'); 
include('./config/config.php'); 
include("./lang/$language.inc.php"); 


$display_block = "
<table align=center>
<tr>
<td>
<ul class=\"postnav\">

<li><a href=\"./manage_invoices.php\">$indx_manage_invoices</a></li>
<ul>

</td>
</tr>
</table>

<table align=center>
<tr>
<td>
<ul class=\"postnav\">
<li><a href=\"./invoice_itemised.php\">$indx_invoice_itemised</a></li>
<li><a href=\"./invoice_total.php\">$indx_invoice_total</a></li>
<li><a href=\"./invoice_consulting.php\">$indx_invoice_consulting</a></li>
</ul>
</td>
</tr>
</table>
<table align=center>
<tr>
<td>
<ul class=\"postnav\">
<li><a href=\"./insert_customer.php\">$indx_insert_customer</a></li>
<li><a href=\"./insert_biller.php\">$indx_insert_biller</a></li>
<li><a href=\"./insert_product.php\">$indx_insert_product</a></li>
</ul>
</td>
</tr>
</table>";
?>
<html>
<head>
<?php include('./include/menu.php'); ?>	
<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>
<script type="text/javascript" src="include/doFilter.js"></script>

<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<meta name="generator" content="HAPedit 3.1">
<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("ul.postnav a","transparent");
}
</script>
<title>Simple Invoices
</title>
</head>
<body>

<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/style.css">
<br>
<br><br><br><br><Br>


<?php echo $display_block; ?>


</body>
</html>


