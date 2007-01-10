<?php
include_once('./include/include_main.php');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
	window.onload=function(){
	Nifty("div#container");
	Nifty("div#subheader");
	Nifty("div#content,div#nav","same-height small");
	Nifty("div#header,div#footer","small");
}
</script>

<title> Simple Invoices - Tax rate to add</title>
</head>
<?php 
include('./include/include_main.php');
include('./include/validation.php');

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("pt_description","Payment type description");
jsFormValidationEnd();
jsEnd();

#do the product enabled/disblaed drop down
$display_block_enabled = "<select name=\"pt_enabled\">
<option value=\"1\" selected>$wording_for_enabledField</option>
<option value=\"0\">$wording_for_disabledField</option>
</select>";


?>

<BODY>
<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>

<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css">
<br>

<FORM name="frmpost" ACTION="index.php?module=payment_types&view=save" METHOD=POST onsubmit="return frmpost_Validator(this)">
<div id="container">
<div id="header">

<table align=center>
	<tr>
		<td colspan=3 align=center><b>Payment type to add</b></th>
	</tr>
</table>

</div id="header">
<!-- <div id="subheader"> -->

<table align=center>
	<tr>
		<td class="details_screen">Payment type description</td><td><input type=text name="pt_description" size=50></td>
	</tr>
	<tr>
		<td class="details_screen"><?php echo $wording_for_enabledField; ?></td><td><?php echo $display_block_enabled;?></td>
	</tr>
	
</table>
<!-- </div> -->
<div id="footer">
	<input type=submit name="submit" value="Insert Payment Type">
	<input type=hidden name="op" value="insert_payment_type">
</div>


</FORM>
</BODY>
</HTML>







