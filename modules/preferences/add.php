<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("p_description",$LANG['description']);
jsFormValidationEnd();
jsEnd();

#do the product enabled/disblaed drop down
$display_block_enabled = "<select name=\"pref_enabled\">
<option value=\"1\" selected>{$LANG['enabled']}</option>
<option value=\"0\">{$LANG['disabled']}</option>
</select>";


echo <<<EOD

</head>
<BODY>

EOD;

echo <<<EOD

<FORM name="frmpost" ACTION="index.php?module=preferences&view=save" METHOD=POST onsubmit="return frmpost_Validator(this)">

<b>{$LANG['invoice_preference_to_add']}</b>

<hr></hr>


<table align=center>
<tr>
	<td class="details_screen">{$LANG['description']} <a href="./documentation/info_pages/inv_pref_description.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_description" size=25></td>
</tr>
<tr>
	<td class="details_screen">{$LANG['currency_sign']} <a href="./documentation/info_pages/inv_pref_currency_sign.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_currency_sign" size=25></td>
</tr>
<tr>
	<td class="details_screen">{$LANG['invoice_heading']} <a href="./documentation/info_pages/inv_pref_invoice_heading.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_heading" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG['invoice_wording']}
	<a href="./src/documentation/info_pages/inv_pref_invoice_wording.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_wording" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG['invoice_detail_heading']}
	<a href="./src/documentation/info_pages/inv_pref_invoice_detail_heading.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_detail_heading" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG['invoice_detail_line']}
	<a href=",/src/documentation/info_pages/inv_pref_invoice_detail_line.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_detail_line" size=75></td>
</tr>
<tr>
	<td class="details_screen">{$LANG['invoice_payment_method']}
	<a href="./src/documentation/info_pages/inv_pref_invoice_payment_method.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_payment_method" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG['invoice_payment_line_1_name']}
	<a href="./src/documentation/info_pages/inv_pref_payment_line1_name.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_payment_line1_name" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG['invoice_payment_line_1_value']}
	<a href="./src/documentation/info_pages/inv_pref_payment_line1_value.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_payment_line1_value" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG['invoice_payment_line_2_name']}
	<a href="./src/documentation/info_pages/inv_pref_payment_line2_name.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_payment_line2_name" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG['invoice_payment_line_2_value']}
	<a href="./src/documentation/info_pages/inv_pref_payment_line2_value.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td><input type=text name="p_inv_payment_line2_value" size=50></td>
</tr>
<tr>
	<td class="details_screen">{$LANG['enabled']}
	<a href="./src/documentation/info_pages/inv_pref_invoice_enabled.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
	<td>{$display_block_enabled}</td>
</tr>
</table>
<!-- </div> -->
<hr></hr>
	<input type=submit name="submit" value="{$LANG['insert_preference']}">
	<input type=hidden name="op" value="insert_preference">

EOD;
?>
</FORM>
