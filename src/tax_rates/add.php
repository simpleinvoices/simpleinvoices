<?php
include('./include/include_main.php');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 

include('./include/validation.php');

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("tax_description",$LANG_tax_description);
jsValidateifNum("tax_percentage",$LANG_tax_percentage);
jsFormValidationEnd();
jsEnd();

#do the product enabled/disblaed drop down
$display_block_enabled = "<select name=\"tax_enabled\">
<option value=\"1\" selected>$wording_for_enabledField</option>
<option value=\"0\">$wording_for_disabledField</option>
</select>";

?>
</head>

<BODY>
<?php

echo <<<EOD

<FORM name="frmpost" ACTION="index.php?module=tax_rates&view=save" METHOD=POST onsubmit="return frmpost_Validator(this)">

<b>{$LANG_tax_rate_to_add}</b>

 <hr></hr>
       <div id="browser">

<!-- <div id="subheader"> -->

<table align=center>
	<tr>
		<td class="details_screen">{$LANG_tax_description}</td>
		<td><input type=text name="tax_description" size=50></td><td></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_tax_percentage}</td>
		<td><input type=text name="tax_percentage" size=25> %</td>
		<td>{$LANG_ie_10_for_10}</td>
	</tr>
	<tr>
		<td class="details_screen">{$wording_for_enabledField}</td>
		<td>{$display_block_enabled}</td><td></td>
	</tr>
	
</table>
	<hr></hr>
	<input type=submit name="submit" value="{$LANG_insert_tax_rate}">
	<input type=hidden name="op" value="insert_tax_rate">


EOD;
?>
</FORM>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
