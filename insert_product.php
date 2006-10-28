<?php 
include('./include/include_main.php');

#do the product enabled/disblaed drop down
$display_block_enabled = "<select name=\"prod_enabled\">
<option value=\"1\" selected>$wording_for_enabledField</option>
<option value=\"0\">$wording_for_disabledField</option>
</select>";
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php include('./include/menu.php'); ?>
<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#subheader");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>

<script language="javascript" type="text/javascript" src="include/tiny_mce/tiny_mce_src.js"></script>
<script language="javascript" type="text/javascript" src="include/tiny-mce.conf.js"></script>

<title> Simple Invoices - Product to add
</title>

<?php include('./config/config.php'); 
include("./include/validation.php");

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("prod_description","Product Description");
jsValidateifNum("prod_unit_price","Product Unit Price");
jsFormValidationEnd();
jsEnd();

?>
</head>
<BODY>
<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>

<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css">
<br>

<FORM name="frmpost" ACTION="insert_action.php" METHOD=POST onsubmit="return frmpost_Validator(this)">
<div id="container">
<div id="header">

<table align=center>
<tr>
<td colspan=2 align=center><b>Product to add</b></th>
</tr>
</table>

</div id="header">
<div id="subheader">

<table align=center>

	<tr>
		<td>Product Description</td><td><input type=text name="prod_description" size=50></td>
	</tr>
	<tr>
		<td>Product Unit Price</td><td><input type=text name="prod_unit_price" size=25></td>
	</tr>
        <tr>
                <td><?php echo $lang_notes;?></td><td><textarea input type=text name='prod_notes' rows=8 cols=50><?php echo $prod_notesField;?></textarea></td>
        </tr>
	<tr>
		<td>Product Enabled</td><td><?php echo $display_block_enabled;?></td>
	</tr>

</table>
</div>
<div id="footer">
	<input type=submit name="submit" value="Insert Product">
	<input type=hidden name="op" value="insert_product">
</div>


</FORM>
</BODY>
</HTML>







