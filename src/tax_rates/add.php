<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("tax_description",$LANG['tax_description']);
jsValidateifNum("tax_percentage",$LANG['tax_percentage']);
jsFormValidationEnd();
jsEnd();

#do the product enabled/disblaed drop down
$display_block_enabled = <<<EOD
<select name="tax_enabled">
<option value="1" selected>$wording_for_enabledField</option>
<option value="0">$wording_for_disabledField</option>
</select>
EOD;


echo <<<EOD

<FORM name="frmpost" ACTION="index.php?module=tax_rates&view=save" METHOD=POST onsubmit="return frmpost_Validator(this)">

<b>{$LANG['tax_rate_to_add']}</b>

 <hr></hr>

<table align=center>
	<tr>
		<td class="details_screen">{$LANG['tax_description']}</td>
		<td><input type=text name="tax_description" size=50></td><td></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG['tax_percentage']}</td>
		<td><input type=text name="tax_percentage" size=25> %</td>
		<td>{$LANG['ie_10_for_10']}</td>
	</tr>
	<tr>
		<td class="details_screen">{$wording_for_enabledField}</td>
		<td>{$display_block_enabled}</td><td></td>
	</tr>
	
</table>
	<hr></hr>
	<input type=submit name="submit" value="{$LANG['insert_tax_rate']}">
	<input type=hidden name="op" value="insert_tax_rate">



</FORM>
EOD;
?>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
