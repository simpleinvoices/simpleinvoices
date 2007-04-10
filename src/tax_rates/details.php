<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#table

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("tax_description",$LANG_tax_description);
jsValidateifNum("tax_percentage",$LANG_tax_percentage);
jsFormValidationEnd();
jsEnd();



#get the invoice id
$tax_rate_id = $_GET['submit'];


$tax = getTaxRate($tax_rate_id);
$wording_for_enabled = $tax['tax_enabled'] == 1 ? $wording_for_enabledField:$wording_for_disabledField;



if ($_GET['action'] === 'view') {

	$display_block = <<<EOD

        <b>{$LANG['tax_rate']} ::
        <a href="index.php?module=tax_rates&view=details&submit=$tax[tax_id]&action=edit">{$LANG['edit']}</a></b>

	<hr></hr>

	<table align="center">
	<tr>
		<td class="details_screen">{$LANG['tax_rate_id']}</td><td>$tax[tax_id]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG['description']}</td><td>$tax[tax_description]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG['tax_percentage']}</td><td>$tax[tax_percentage]</td>
	</tr>
	<tr>
		<td class="details_screen">{$wording_for_enabledField}</td><td>{$wording_for_enabled}</td>
	</tr>
	</table>
	<hr></hr>

EOD;
$footer = <<<EOD

<a href='index.php?module=tax_rates&view=details&submit=$tax[tax_id]&action=edit'>{$LANG['edit']}</a>

EOD;
}

else if ($_GET['action'] === 'edit') {

#do the product enabled/disblaed drop down
$display_block_enabled = <<<EOD
<select name="tax_enabled">
<option value="$tax[tax_enabled]" selected style="font-weight: bold">$wording_for_enabled</option>
<option value="1">$wording_for_enabledField</option>
<option value="0">$wording_for_disabledField</option>
</select>
EOD;

$display_block = <<<EOD

        <b>{$LANG['tax_rate']}</b> 

	<hr></hr>

	<table align="center">
	<tr>
		<td class="details_screen">{$LANG['tax_rate_id']}</td><td>$tax[tax_id]</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG['description']}</td>
		<td><input type="text" name="tax_description" value="{$tax['tax_description']}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG['tax_percentage']}</td>
		<td><input type="text" name="tax_percentage" value="{$tax['tax_percentage']}" size="10" />%</td>
	</tr>
	<tr>
		<td class="details_screen">{$wording_for_enabledField} </td><td>{$display_block_enabled}</td>
	</tr>
	</table>
	<hr></hr>
EOD;

$footer = <<<EOD

<input type="submit" name="cancel" value="{$LANG['cancel']}" />
<input type="submit" name="save_tax_rate" value="{$LANG['save_tax_rate']}" />
<input type="hidden" name="op" value="edit_tax_rate" />

EOD;
}


echo <<<EOD

<form name="frmpost" action="index.php?module=tax_rates&view=save&submit={$_GET['submit']}"
 method="post" onsubmit="return frmpost_Validator(this)">

$display_block

$footer


</form>
EOD;
?>