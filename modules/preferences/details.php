<?php


//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("pref_description","Description");
jsFormValidationEnd();
jsEnd();



#get the invoice id
$preference_id = $_GET['submit'];


$print_preferences = "SELECT * FROM {$tb_prefix}preferences where pref_id = $preference_id";
$result_print_preferences  = mysql_query($print_preferences, $conn) or die(mysql_error());

while ($pref = mysql_fetch_array($result_print_preferences)) {
	if ($pref['pref_enabled'] == 1) {
		$wording_for_enabled = $LANG['enabled'];
	} else {
		$wording_for_enabled = $LANG['disabled'];
	}
};

if (  $_GET['action'] === 'view' ) {

	$display_block = <<<EOD

	<b>Preference :: <a href='index.php?module=preferences&view=details&submit={$pref['pref_id']}&action=edit'>Edit</a></b>
	<hr></hr>

	
	<table align=center>
		<tr>
  			<td class='details_screen'>Preference ID</td><td>{$pref['pref_id']}</td>
                </tr>
		<tr>	
			<td class='details_screen'>Description <a href="./modules/documentation/info_pages/inv_pref_description.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$pref['pref_description']}</td>
                </tr>
                <tr>
			<td class='details_screen'>Currency sign <a href="./modules/documentation/info_pages/inv_pref_currency_sign.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$pref['pref_currency_sign']}</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice heading <a href="./modules/documentation/info_pages/inv_pref_invoice_heading.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$pref['pref_inv_heading']}</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice wording <a href="./modules/documentation/info_pages/inv_pref_invoice_wording.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$pref['pref_inv_wording']}</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice detail heading <a href="./modules/documentation/info_pages/inv_pref_invoice_detail_heading.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$pref['pref_inv_detail_heading']}</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice detail line <a href="./modules/documentation/info_pages/inv_pref_invoice_detail_line.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$pref['pref_inv_detail_line']}</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice payment method <a href="./modules/documentation/info_pages/inv_pref_invoice_payment_method.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$pref['pref_inv_payment_method']}</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice payment line1 name <a href="./modules/documentation/info_pages/inv_pref_payment_line1_name.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$pref['pref_inv_payment_line1_name']}</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice payment line1 value <a href="./modules/documentation/info_pages/inv_pref_payment_line1_value.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$pref['pref_inv_payment_line1_value']}</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice payment line2 name <a href="./modules/documentation/info_pages/inv_pref_payment_line2_name.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$pref['pref_inv_payment_line2_name']}</td>
                </tr>
                <tr>
			<td class='details_screen'>Invoice payment line2 value <a href="./modules/documentation/info_pages/inv_pref_payment_line2_value.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>{$pref['pref_inv_payment_line2_value']}</td>
		</tr>
	        <tr>
        	        <td class='details_screen'>$LANG['enabled'] <a href="./modules/documentation/info_pages/inv_pref_invoice_enabled.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>$wording_for_enabled</td>
	        </tr>	
		<tr>
			<td colspan=2 align=center></td>
		</tr>
		<tr>
			<td colspan=2 align=center class="align_center"><a href="./modules/documentation/info_pages/inv_pref_what_the.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img> Whats all this "Invoice Preference" stuff about?</a></td>
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
<option value="{$pref['pref_enabled']}" selected style="font-weight: bold;">$wording_for_enabled</option>
<option value="1">{$LANG['enabled']}</option>
<option value="0">{$LANG['disabled']}</option>
</select>
EOD;

	$display_block =  <<<EOD
	<b>Preferences</b>
	<hr></hr>

        <table align=center>
                <tr>
                        <td class='details_screen'>Preference ID</td><td>{$pref['pref_id']}</td>
                </tr>
                <tr>
                        <td class='details_screen'>Description <a href="./modules/documentation/info_pages/inv_pref_description.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_description' value='{$pref['pref_description']}' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Currenc sign <a href="./modules/documentation/info_pages/inv_pref_currency_sign.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_currency_sign' value='{$pref['pref_currency_sign']}' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice heading <a href="./modules/documentation/info_pages/inv_pref_invoice_heading.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a><td><input type=text name='pref_inv_heading' value='{$pref['pref_inv_heading']}' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice wording <a href="./modules/documentation/info_pages/inv_pref_invoice_wording.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_wording' value='{$pref['pref_inv_wording']}' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice detail heading <a href="./modules/documentation/info_pages/inv_pref_invoice_detail_heading.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_detail_heading' value='{$pref['pref_inv_detail_heading']}' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice detail line <a href="./modules/documentation/info_pages/inv_pref_invoice_detail_line.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_detail_line' value='{$pref['pref_inv_detail_line']}' size=75></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment method <a href="./modules/documentation/info_pages/inv_pref_invoice_payment_method.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_payment_method' value='{$pref['pref_inv_payment_method']}' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment line1 name <a href="./modules/documentation/info_pages/inv_pref_payment_line1_name.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_payment_line1_name' value='{$pref['pref_inv_payment_line1_name']}' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment line1 value <a href="./modules/documentation/info_pages/inv_pref_payment_line1_value.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_payment_line1_value' value='{$pref['pref_inv_payment_line1_value']}' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment line2 name <a href="./modules/documentation/info_pages/inv_pref_payment_line2_name.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_payment_line2_name' value='{$pref['pref_inv_payment_line2_name']}' size=50></td>
                </tr>
                <tr>
                        <td class='details_screen'>Invoice payment line2 value <a href="./modules/documentation/info_pages/inv_pref_payment_line2_value.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td><input type=text name='pref_inv_payment_line2_value' value='{$pref['pref_inv_payment_line2_value']}' size=50></td>
                </tr>
	        <tr>
        	        <td class='details_screen'>{$LANG['enabled']} <a href="./modules/documentation/info_pages/inv_pref_invoice_enabled.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td><td>$display_block_enabled</td>
	        </tr>
                <tr>
                        <td colspan=2 align=center></td>
                </tr>
                <tr>
                        <td colspan=2 align=center class="align_center"><a href="./modules/documentation/info_pages/inv_pref_what_the.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img> Whats all this "Invoice Preference" stuff about?</a></td>
                </tr>

                </table>
		<hr></hr>
EOD;

$footer =  <<<EOD
<input type=submit name='action' value='{$LANG['cancel']}'>
<input type=submit name='save_preference' value='{$LANG['save']}'>
<input type=hidden name='op' value='edit_preference'>
EOD;

}


echo <<<EOD
<form name="frmpost" action="index.php?module=preferences&view=save&submit={$_GET['submit']}" method="post" onsubmit="return frmpost_Validator(this)">
	$display_block
	$footer
EOD;
?>
