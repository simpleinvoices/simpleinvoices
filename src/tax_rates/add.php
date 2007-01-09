<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
include('./include/include_main.php');
include('./include/validation.php');
echo <<<EOD
<title>Simple Invoices :: {$LANG_tax_rate_to_add}</title>
<link rel="stylesheet" type="text/css" href="themes/{$theme}/tables.css">

EOD;
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
<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#subheader");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>
</head>

<BODY>
<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
echo <<<EOD

<br>

<FORM name="frmpost" ACTION="index.php?module=tax_rates&view=save" METHOD=POST onsubmit="return frmpost_Validator(this)">
<div id="container">
<div id="header"><b>{$LANG_tax_rate_to_add}</b></div id="header">
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
<!-- </div> -->

<div id="footer">
	<input type=submit name="submit" value="{$LANG_insert_tax_rate}">
	<input type=hidden name="op" value="insert_tax_rate">
</div>

EOD;
?>

</FORM>
</BODY>
</HTML>
