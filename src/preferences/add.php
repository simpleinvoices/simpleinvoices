<?php
include('./include/include_main.php');

//stop the direct browsing to this file - let index.php handle which files get displayed
if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}


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


echo <<<EOD

	<script type="text/javascript" src="./src/include/js/ibox.js"></script>
	<link rel="stylesheet" href="./src/include/css/ibox.css" type="text/css"  media="screen"/>

</head>
<BODY>

EOD;

echo <<<EOD

<FORM name="frmpost" ACTION="index.php?module=preferences&view=save" METHOD=POST onsubmit="return frmpost_Validator(this)">

<b>{$LANG_invoice_preference_to_add}</b>

<hr></hr>


<table align=center>
<tr>
	<td class="details_screen">{$LANG_description} <a href="documentation/info_pages/inv_pref_description.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_description" size=25></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_currency_sign} <a href="documentation/info_pages/inv_pref_currency_sign.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_currency_sign" size=25></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_invoice_heading} <a href="documentation/info_pages/inv_pref_invoice_heading.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_heading" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_invoice_wording}
	<a href="documentation/info_pages/inv_pref_invoice_wording.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_wording" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_invoice_detail_heading}
	<a href="documentation/info_pages/inv_pref_invoice_detail_heading.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_detail_heading" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_invoice_detail_line}
	<a href="documentation/info_pages/inv_pref_invoice_detail_line.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_detail_line" size=75></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_invoice_payment_method}
	<a href="documentation/info_pages/inv_pref_invoice_payment_method.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_payment_method" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_invoice_payment_line_1_name}
	<a href="documentation/info_pages/inv_pref_payment_line1_name.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_payment_line1_name" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_invoice_payment_line_1_value}
	<a href="documentation/info_pages/inv_pref_payment_line1_value.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_payment_line1_value" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_invoice_payment_line_2_name}
	<a href="documentation/info_pages/inv_pref_payment_line2_name.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_payment_line2_name" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG_invoice_payment_line_2_value}
	<a href="documentation/info_pages/inv_pref_payment_line2_value.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_payment_line2_value" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$wording_for_enabledField}
	<a href="documentation/info_pages/inv_pref_invoice_enabled.html" rel="ibox&height=400"><img src="./images/common/help-small.png"></img></a></td>
	<td>{$display_block_enabled}</td>
</tr>
</table>
<!-- </div> -->
<hr></hr>
	<input type=submit name="submit" value="{$LANG_insert_preference}">
	<input type=hidden name="op" value="insert_preference">

EOD;
?>
</FORM>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
