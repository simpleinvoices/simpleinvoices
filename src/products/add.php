<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


#include('./include/functions.php');
/* validataion code */


jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("prod_description",$LANG_product_description);
jsValidateifNum("prod_unit_price",$LANG_product_unit_price);
jsFormValidationEnd();
jsEnd();

/* end validataion code */

#do the product enabled/disblaed drop down
$display_block_enabled = <<<EOD
<select name="prod_enabled">
<option value="1" selected>$wording_for_enabledField</option>
<option value="0">$wording_for_disabledField</option>
</select>
EOD;

#get custom field labels
$customFieldLabel = getCustomFieldLabels("product");

echo <<<EOD




<FORM name="frmpost" ACTION="index.php?module=products&view=save" METHOD=POST onsubmit="return frmpost_Validator(this)">

<div id="top"><b>&nbsp;{$LANG_product_to_add}&nbsp;</b></div>
 <hr></hr>

<table align=center>
	<tr>
		<td class="details_screen">{$LANG_product_description}</td>
		<td><input type=text name="prod_description" size=50></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_product_unit_price}</td>
		<td><input type=text name="prod_unit_price" size=25></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel['1']} <a href="./src/documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="prod_custom_field1" size=50></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel['2']} <a href="./src/documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="prod_custom_field2" size=50></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel['3']} <a href="./src/documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="prod_custom_field3" size=50></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel['4']} <a href="./src/documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type=text name="prod_custom_field4" size=50></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_notes}</td>
		<td><textarea input type=text name='prod_notes' rows=8 cols=50>{$prod_notesField}</textarea></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG_product_enabled}</td>
		<td>{$display_block_enabled}</td>
	</tr>
</table>
<!-- </div> -->
<hr></hr>
	<input type=submit name="submit" value="{$LANG_insert_product}">
	<input type=hidden name="op" value="insert_product">
</FORM>
EOD;
?>