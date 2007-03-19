<?php
include("./include/include_main.php");

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

include('./include/validation.php');

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("pref_description","Description");
jsFormValidationEnd();
jsEnd();



#get the invoice id
$preference_id = $_GET['submit'];


$print_preferences = "SELECT * FROM {$tb_prefix}preferences where pref_id = $preference_id";
$result_print_preferences  = mysql_query($print_preferences, $conn) or die(mysql_error());

while ($Array_preferences = mysql_fetch_array($result_print_preferences)) {
	$pref_idField = $Array_preferences['pref_id'];
	$pref_descriptionField = $Array_preferences['pref_description'];
	$pref_currency_signField = $Array_preferences['pref_currency_sign'];
	$pref_inv_headingField = $Array_preferences['pref_inv_heading'];
	$pref_inv_wordingField = $Array_preferences['pref_inv_wording'];
	$pref_inv_detail_headingField = $Array_preferences['pref_inv_detail_heading'];
	$pref_inv_detail_lineField = $Array_preferences['pref_inv_detail_line'];
	$pref_inv_payment_methodField = $Array_preferences['pref_inv_payment_method'];
	$pref_inv_payment_line1_nameField = $Array_preferences['pref_inv_payment_line1_name'];
	$pref_inv_payment_line1_valueField = $Array_preferences['pref_inv_payment_line1_value'];
	$pref_inv_payment_line2_nameField = $Array_preferences['pref_inv_payment_line2_name'];
	$pref_inv_payment_line2_valueField = $Array_preferences['pref_inv_payment_line2_value'];
	$pref_enabledField = $Array_preferences['pref_enabled'];

	if ($pref_enabledField == 1) {
		$wording_for_enabled = $wording_for_enabledField;
	} else {
		$wording_for_enabled = $wording_for_disabledField;
	}

};

if (  $_GET['action'] === 'view' ) {

	$display_block = <<<EOD

	<b>Preference :: <a href='index.php?module=preferences&view=details&submit=$pref_idField&action=edit'>Edit</a></b>
	<hr></hr>

	
	<table align=center>
		<tr>
  			<td class='details_screen'>Preference ID</td><td>$pref_idField</td>
                </tr>
		<tr>	
			<td class='details_screen'>Description <a href="./documentation/info_pages/inv_pref_description.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>$pref_descriptionField</td>
                </tr>
                <tr>
			<td class='details_screen'>Currency sign <a href="./documentation/info_pages/inv_pref_currency_sign.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>$pref_currency_signField</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice heading <a href="./documentation/info_pages/inv_pref_invoice_heading.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>$pref_inv_headingField</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice wording <a href="./documentation/info_pages/inv_pref_invoice_wording.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>$pref_inv_wordingField</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice detail heading <a href="./documentation/info_pages/inv_pref_invoice_detail_heading.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>$pref_inv_detail_headingField</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice detail line <a href="./documentation/info_pages/inv_pref_invoice_detail_line.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>$pref_inv_detail_lineField</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice payment method <a href="./documentation/info_pages/inv_pref_invoice_payment_method.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>$pref_inv_payment_methodField</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice payment line1 name <a href="./documentation/info_pages/inv_pref_payment_line1_name.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>$pref_inv_payment_line1_nameField</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice payment line1 value <a href="./documentation/info_pages/inv_pref_payment_line1_value.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>$pref_inv_payment_line1_valueField</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice payment line2 name <a href="./documentation/info_pages/inv_pref_payment_line2_name.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>$pref_inv_payment_line2_nameField</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice payment line2 value <a href="./documentation/info_pages/inv_pref_payment_line2_value.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>$pref_inv_payment_line2_valueField</td>
		</tr>
	        <tr>
        	        <td class='details_screen'>$wording_for_enabledField <a href="./documentation/info_pages/inv_pref_invoice_enabled.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>$wording_for_enabled</td>
	        </tr>	
		<tr>
			<td colspan=2 align=center></td>
		</tr>
		<tr>
			<td colspan=2 align=center class="align_center"><a href="./documentation/info_pages/inv_pref_what_the.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img> Whats all this "Invoice Preference" stuff about?</a></td>
		</tr>
		</table>
		<hr></hr>
EOD;

	$footer =  <<<EOD

<a href='index.php?module=preferences&view=details&submit=$pref_idField&action=edit'>Edit</a>
EOD;

}


else if (  $_GET['action'] === 'edit' ) {

	$display_block_enabled = <<<EOD
<select name="pref_enabled">
<option value="$pref_enabledField" selected style="font-weight: bold;">$wording_for_enabled</option>
<option value="1">$wording_for_enabledField</option>
<option value="0">$wording_for_disabledField</option>
</select>
EOD;

	$display_block =  <<<EOD
	<b>Preferences</b>
	<hr></hr>

        <table align=center>
                <tr>
                        <td class='details_screen'>Preference ID</td><td>$pref_idField</td>
                </tr>
                <tr>
                        <td class='details_screen'>Description <a href="./documentation/info_pages/inv_pref_description.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_description' value='$pref_descriptionField' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Currenc sign <a href="./documentation/info_pages/inv_pref_currency_sign.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_currency_sign' value='$pref_currency_signField' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice heading <a href="./documentation/info_pages/inv_pref_invoice_heading.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a><td><input type=text name='pref_inv_heading' value='$pref_inv_headingField' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice wording <a href="./documentation/info_pages/inv_pref_invoice_wording.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_wording' value='$pref_inv_wordingField' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice detail heading <a href="./documentation/info_pages/inv_pref_invoice_detail_heading.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_detail_heading' value='$pref_inv_detail_headingField' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice detail line <a href="./documentation/info_pages/inv_pref_invoice_detail_line.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_detail_line' value='$pref_inv_detail_lineField' size=75></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment method <a href="./documentation/info_pages/inv_pref_invoice_payment_method.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_payment_method' value='$pref_inv_payment_methodField' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment line1 name <a href="./documentation/info_pages/inv_pref_payment_line1_name.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_payment_line1_name' value='$pref_inv_payment_line1_nameField' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment line1 value <a href="./documentation/info_pages/inv_pref_payment_line1_value.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_payment_line1_value' value='$pref_inv_payment_line1_valueField' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment line2 name <a href="./documentation/info_pages/inv_pref_payment_line2_name.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_payment_line2_name' value='$pref_inv_payment_line2_nameField' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment line2 value <a href="./documentation/info_pages/inv_pref_payment_line2_value.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_payment_line2_value' value='$pref_inv_payment_line2_valueField' size=50></td>
                </tr>
	        <tr>
        	        <td class='details_screen'>$wording_for_enabledField <a href="./documentation/info_pages/inv_pref_invoice_enabled.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>$display_block_enabled</td>
	        </tr>
                <tr>
                        <td colspan=2 align=center></td>
                </tr>
                <tr>
                        <td colspan=2 align=center class="align_center"><a href="./documentation/info_pages/inv_pref_what_the.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img> Whats all this "Invoice Preference" stuff about?</a></td>
                </tr>

                </table>
		<hr></hr>
EOD;

$footer =  <<<EOD
<input type=submit name='action' value='{$LANG_cancel}'>
<input type=submit name='save_preference' value='{$LANG_save}'>
<input type=hidden name='op' value='edit_preference'>
EOD;

}


echo <<<EOD
<form name="frmpost" action="index.php?module=preferences&view=save&submit={$_GET['submit']}" method="post" onsubmit="return frmpost_Validator(this)">
	$display_block
	$footer
EOD;
?>