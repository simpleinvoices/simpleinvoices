<?php
include("./include/include_main.php");

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

include("./include/validation.php");

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("pt_description",$LANG_payment_type_description);
jsFormValidationEnd();
jsEnd();

#get the invoice id
$payment_type_id = $_GET['submit'];


#customer query
$print_payment_type = "SELECT * FROM {$tb_prefix}payment_types WHERE pt_id = $payment_type_id";
$result_payment_type = mysql_query($print_payment_type, $conn) or die(mysql_error());

$paymentType = mysql_fetch_array($result_payment_type);


if ($paymentType['pt_enabled'] == 1) {
	$wording_for_enabled = $wording_for_enabledField;	
} else {
	$wording_for_enabled = $wording_for_disabledField;
}

if ($_GET['action'] === 'view') {

$display_block = <<<EOD
	
	<b>{$LANG_payment_type}</b>
	<hr></hr>

	<table align="center">
	<tr>
		<td class="details_screen">{$LANG_payment_type_id}</td><td>{$paymentType['pt_id']}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_description}</td><td>{$paymentType['pt_description']}</td>
	</tr>
	<tr>
		<td class="details_screen">{$wording_for_enabledField}</td><td>{$wording_for_enabled}</td>
	</tr>
	</table>
	<hr></hr>
EOD;
$footer = "

<a href='index.php?module=payment_types&view=details&submit={$pt_idField}&action=edit'>{$LANG_edit}</a>
";

}

else if ($_GET['action'] === 'edit') {

#do the product enabled/disblaed drop down
$display_block_enabled = <<<EOD
<select name="pt_enabled">
<option value="{$paymentType['pt_enabled']}" selected style="font-weight: bold">$wording_for_enabled</option>
<option value="1">$wording_for_enabledField</option>
<option value="0">$wording_for_disabledField</option>
</select>
EOD;

$display_block = <<<EOD

	<b>{$LANG_payment_type_edit}</b>
	<hr></hr>

	<table align=center>
	<tr>
		<td class="details_screen">{$LANG_payment_type_id}</td>
		<td>{$paymentType['pt_id']}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_description}</td>
		<td><input type="text" name="pt_description" value="{$paymentType['pt_description']}"
		 size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$wording_for_enabledField} </td>
		<td>{$display_block_enabled}</td>
	</tr>
	</table>
	<hr></hr>
EOD;

$footer = <<<EOD

<input type="submit" name="cancel" value="{$LANG_cancel}" />
<input type="submit" name="save_payment_type" value="{$LANG_save_payment_type}" />
<input type="hidden" name="op" value="edit_payment_type" />

EOD;
}

echo <<<EOD

<form name="frmpost" action="index.php?module=payment_types&view=save&submit={$_GET['submit']}" method="post" onsubmit="return frmpost_Validator(this)">


{$display_block}

{$footer}
EOD;
?>