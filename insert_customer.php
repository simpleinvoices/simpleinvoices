<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php
include('./include/include_main.php');

/* validataion code */
include("./include/validation.php");

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("c_name",$LANG_customer_name);
jsFormValidationEnd();
jsEnd();

/* end validataion code */

#do the product enabled/disblaed drop down
$display_block_enabled = "<select name=\"c_enabled\">
<option value=\"1\" selected>$wording_for_enabledField</option>
<option value=\"0\">$wording_for_disabledField</option>
</select>";

?>
<script type="text/javascript" src="niftycube.js"></script>
<script type="text/javascript">
window.onload=function(){
Nifty("div#container");
Nifty("div#content,div#nav","same-height small");
Nifty("div#header,div#footer","small");
}
</script>

<script language="javascript" type="text/javascript" src="include/tiny_mce/tiny_mce_src.js"></script>
<script language="javascript" type="text/javascript">
<script language="javascript" type="text/javascript" src="include/tiny-mce.conf.js"></script>
<?php
echo <<<EOD
<title>{$title} :: {$LANG_customer_add}</title>
<link rel="stylesheet" type="text/css" href="themes/{$theme}/tables.css">
</head>
<BODY>

EOD;
$mid->printMenu('hormenu1');
$mid->printFooter();

echo <<<EOD

<br>

<FORM name="frmpost" ACTION="insert_action.php" METHOD=POST onsubmit="return frmpost_Validator(this)">

<div id="container">
<div id="header"><b>{$LANG_customer_add}</b> </div>

<table align=center>
<tr>
	<td>{$LANG_customer_name}</td><td><input type=text name="c_name" size=25></td>
</tr>
</tr>
	<td>{$LANG_customer_contact}</td><td><input type=text name="c_attention" size=25></td>
</tr>
<tr>
	<td>{$LANG_address_street}</td><td><input type=text name="c_street_address" size=25></td>
</tr>
<tr>
	<td>{$LANG_address_city}</td><td><input type=text name="c_city" size=25></td>
</tr>
<tr>
	<td>{$LANG_address_state}</td><td><input type=text name="c_state" size=25></td>
</tr>
<tr>
	<td>{$LANG_address_zip}</td><td><input type=text name="c_zip_code" size=25></td>
</tr>
<tr>
	<td>{$LANG_address_country} ({$LANG_optional})</td><td><input type=text name="c_country" size=75></td>
</tr>
<tr>
	<td>{$LANG_phone}</td><td><input type=text name="c_phone" size=25></td>
</tr>
<tr>
	<td>{$LANG_fax}</td><td><input type=text name="c_fax" size=25></td>
</tr>
<tr>
	<td>{$LANG_email}</td><td><input type=text name="c_email" size=25></td>
</tr>
<tr>    
	<td>{$LANG_notes}</td><td><textarea name='c_notes' rows=8 cols=50></textarea></td>
</tr>
<tr>
<td>{$wording_for_enabledField}</td><td>{$display_block_enabled}</td>
</tr>

</table>

<div id="footer">
	<input type=submit name="submit" value="{$LANG_insert_customer}">
	<input type=hidden name="op" value="insert_customer">
</div>

EOD;
?>
</div>
</div>
</FORM>
</BODY>
</HTML>