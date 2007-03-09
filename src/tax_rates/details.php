<?php
include("./include/include_main.php");

//stop the direct browsing to this file - let index.php handle which files get displayed
if (!defined("BROWSE")) {
   echo "You Cannot Access This Script Directly, Have a Nice Day.";
   exit();
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
#table
include('./include/validation.php');

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("tax_description",$LANG_tax_description);
jsValidateifNum("tax_percentage",$LANG_tax_percentage);
jsFormValidationEnd();
jsEnd();



#get the invoice id
$tax_rate_id = $_GET['submit'];


#Info from DB print
$conn = mysql_connect("$db_host","$db_user","$db_password");
mysql_select_db("$db_name",$conn);



#customer query
$print_tax_rate = "SELECT * FROM si_tax WHERE tax_id = $tax_rate_id";
$result_print_tax_rate = mysql_query($print_tax_rate, $conn) or die(mysql_error());


while ($Array = mysql_fetch_array($result_print_tax_rate) ) {
	$tax_idField = $Array['tax_id'];
	$tax_descriptionField = $Array['tax_description'];
	$tax_percentageField = $Array['tax_percentage'];
	$tax_enabledField = $Array['tax_enabled'];

	if ($tax_enabledField == 1) {
		$wording_for_enabled = $wording_for_enabledField;	
	} else {
		$wording_for_enabled = $wording_for_disabledField;
	}

};


if ($_GET['action'] === 'view') {

	$display_block = <<<EOD

        <b>{$LANG_tax_rate} ::
        <a href="index.php?module=tax_rates&view=details&submit={$tax_idField}&action=edit">{$LANG_edit}</a></b>

	<hr></hr>

	<table align="center">
	<tr>
		<td class="details_screen">{$LANG_tax_rate_id}</td><td>{$tax_idField}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_description}</td><td>{$tax_descriptionField}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_tax_percentage}</td><td>{$tax_percentageField}</td>
	</tr>
	<tr>
		<td class="details_screen">{$wording_for_enabledField}</td><td>{$wording_for_enabled}</td>
	</tr>
	</table>
	<hr></hr>

EOD;
$footer = "

<a href='index.php?module=tax_rates&view=details&submit={$tax_idField}&action=edit'>{$LANG_edit}</a>

";

}

else if ($_GET['action'] === 'edit') {

#do the product enabled/disblaed drop down
$display_block_enabled = "<select name=\"tax_enabled\">
<option value=\"$tax_enabledField\" selected style=\"font-weight: bold\">$wording_for_enabled</option>
<option value=\"1\">$wording_for_enabledField</option>
<option value=\"0\">$wording_for_disabledField</option>
</select>";

$display_block = <<<EOD

        <b>{$LANG_tax_rate}</b> 

	<hr></hr>

	<table align="center">
	<tr>
		<td class="details_screen">{$LANG_tax_rate_id}</td><td>{$tax_idField}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_description}</td>
		<td><input type="text" name="tax_description" value="{$tax_descriptionField}" size="50" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_tax_percentage}</td>
		<td><input type="text" name="tax_percentage" value="{$tax_percentageField}" size="10" />%</td>
	</tr>
	<tr>
		<td class="details_screen">{$wording_for_enabledField} </td><td>{$display_block_enabled}</td>
	</tr>
	</table>
	<hr></hr>
EOD;

$footer = <<<EOD

<input type="submit" name="cancel" value="{$LANG_cancel}" />
<input type="submit" name="save_tax_rate" value="{$LANG_save_tax_rate}" />
<input type="hidden" name="op" value="edit_tax_rate" />

EOD;
}

?>
</head>
<body>
<?php

echo <<<EOD

<form name="frmpost" action="index.php?module=tax_rates&view=save&submit={$_GET['submit']}"
 method="post" onsubmit="return frmpost_Validator(this)">

{$display_block}

{$footer}

EOD;
?>
</form>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
