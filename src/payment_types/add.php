<?php
include_once('./include/include_main.php');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<?php 
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


<FORM name="frmpost" ACTION="index.php?module=payment_types&view=save" METHOD=POST onsubmit="return frmpost_Validator(this)">
		
<b>Payment type to add</b>
 <hr></hr>
       <div id="browser">

<table align=center>
	<tr>
		<td class="details_screen">Payment type description</td><td><input type=text name="pt_description" size=50></td>
	</tr>
	<tr>
		<td class="details_screen"><?php echo $wording_for_enabledField; ?></td><td><?php echo $display_block_enabled;?></td>
	</tr>
	
</table>
	<hr></hr>
	<input type=submit name="submit" value="Insert Payment Type">
	<input type=hidden name="op" value="insert_payment_type">

<?php include("footer.inc.php");?>

</FORM>
</BODY>
</HTML>
