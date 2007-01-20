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
jsValidateRequired("p_description",$LANG_description);
jsFormValidationEnd();
jsEnd();

#do the product enabled/disblaed drop down
$display_block_enabled = "<select name=\"pref_enabled\">
<option value=\"1\" selected>$wording_for_enabledField</option>
<option value=\"0\">$wording_for_disabledField</option>
</select>";



?>

<?php include('./include/menu.php');
echo <<<EOD
    <script type="text/javascript" src="./include/jquery.js"></script>
    <script type="text/javascript" src="./include/jquery.thickbox.js"></script>

    <link rel="stylesheet" type="text/css" href="themes/{$theme}/jquery.thickbox.css" media="all"/>

</head>
<BODY>

EOD;

echo <<<EOD

<FORM name="frmpost" ACTION="index.php?module=preferences&view=save" METHOD=POST onsubmit="return frmpost_Validator(this)">

<b>{$LANG_invoice_preference_to_add}</b>

<hr></hr>
<div id="browser">


<table align=center>
<tr>
	<td class="details_screen">{$LANG_description} <a href="documentation/text/inv_pref_description.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Preference description" class="thickbox">*</a></td>
	<td><input type=text name="p_description" size=25></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_currency_sign} <a href="documentation/text/inv_pref_currency_sign.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Currency sign" class="thickbox">*</a></td>
	<td><input type=text name="p_currency_sign" size=25></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_invoice_heading} <a href="documentation/text/inv_pref_invoice_heading.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Invoice heading" class="thickbox">*</a></td>
	<td><input type=text name="p_inv_heading" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_invoice_wording}
	<a href="documentation/text/inv_pref_invoice_wording.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Preference wording" class="thickbox">*</a></td>
	<td><input type=text name="p_inv_wording" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_invoice_detail_heading}
	<a href="documentation/text/inv_pref_invoice_detail_heading.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Detail heading" class="thickbox">*</a></td>
	<td><input type=text name="p_inv_detail_heading" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_invoice_detail_line}
	<a href="documentation/text/inv_pref_invoice_detail_line.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Details line" class="thickbox">*</a></td>
	<td><input type=text name="p_inv_detail_line" size=75></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_invoice_payment_method}
	<a href="documentation/text/inv_pref_invoice_payment_method.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Payment method" class="thickbox">*</a></td>
	<td><input type=text name="p_inv_payment_method" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_invoice_payment_line_1_name}
	<a href="documentation/text/inv_pref_payment_line1_name.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Payment line 1 name" class="thickbox">*</a></td>
	<td><input type=text name="p_inv_payment_line1_name" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_invoice_payment_line_1_value}
	<a href="documentation/text/inv_pref_payment_line1_value.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Payment line 1 value" class="thickbox">*</a></td>
	<td><input type=text name="p_inv_payment_line1_value" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_invoice_payment_line_2_name}
	<a href="documentation/text/inv_pref_payment_line2_name.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Payment live 2 name" class="thickbox">*</a></td>
	<td><input type=text name="p_inv_payment_line2_name" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_invoice_payment_line_2_value}
	<a href="documentation/text/inv_pref_payment_line2_value.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Payment line 2 value" class="thickbox">*</a></td>
	<td><input type=text name="p_inv_payment_line2_value" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$wording_for_enabledField}
	<a href="documentation/text/inv_pref_invoice_enabled.html?keepThis=true&TB_iframe=true&height=300&width=500" title="Info :: Preference enabled" class="thickbox">*</a></td>
	<td>{$display_block_enabled}</td>
</tr>
</table>
<!-- </div> -->

	<input type=submit name="submit" value="{$LANG_insert_preference}">
	<input type=hidden name="op" value="insert_preference">

EOD;

include("footer.inc.php");
?>
</FORM>
</BODY>
</HTML>
