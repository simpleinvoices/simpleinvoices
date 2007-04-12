<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


#table


jsBegin();
jsFormValidationBegin("frmpost");
jsValidateRequired("prod_description",$LANG['product_description']);
jsValidateifNum("prod_unit_price",$LANG['product_unit_price']);
jsFormValidationEnd();
jsEnd();


#get the invoice id
$product_id = $_GET['submit'];

#customer query
$print_product = "SELECT * FROM {$tb_prefix}products WHERE prod_id = $product_id";
$result_print_product = mysql_query($print_product, $conn) or die(mysql_error());

$product = mysql_fetch_array($result_print_product);

if ($product['prod_enabled'] == 1) {
	$wording_for_enabled = $wording_for_enabledField;
} else {
	$wording_for_enabled = $wording_for_disabledField;
}

#get custom field labels
$customFieldLabel = getCustomFieldLabels("product");


if ($_GET['action'] == "view") {

	$display_block = <<<EOD
	<b>{$LANG['products']} ::
	<a href="index.php?module=products&view=details&submit={$product['prod_id']}&action=edit">{$LANG['edit']}</a></b>
	
 	<hr></hr>

	<table align="center">
	<tr>
		<td class="details_screen">{$LANG['product_id']}</td><td>{$product['prod_id']}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG['product_description']}</td>
		<td>{$product['prod_description']}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG['product_unit_price']}</td>
		<td>{$product['prod_unit_price']}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel['1']} <a href="./documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
		<td>{$product['prod_custom_field1']}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel['2']} <a href="./documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
		<td>{$product['prod_custom_field2']}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel['3']} <a href="./documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
		<td>{$product['prod_custom_field3']}</td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel['4']} <a href="./documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
		<td>{$product['prod_custom_field4']}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG['notes']}</td><td>{$product['prod_notes']}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG['product_enabled']}</td>
		<td>{$wording_for_enabled}</td>
	</tr>
	</table>

EOD;

$footer = <<<EOD
<hr></hr>
<a href="index.php?module=products&view=details&submit={$product['prod_id']}&action=edit">{$LANG['edit']}</a>

EOD;

}

else if ($_GET['action'] == "edit") {

#do the product enabled/disblaed drop down
$display_block_enabled = <<<EOD
<select name="prod_enabled">
<option value="{$product['prod_enabled']}" selected style="font-weight: bold">$wording_for_enabled</option>
<option value="1">$wording_for_enabledField</option>
<option value="0">$wording_for_disabledField</option>
</select>
EOD;

$display_block = <<<EOD
	<b>{$LANG['product_edit']}</b>
	<hr></hr>

	<table align="center">
	<tr>
		<td class="details_screen">{$LANG['product_id']}</td><td>{$product['prod_id']}</td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG['product_description']}</td>
		<td><input type="text" name="prod_description" size="50" value="{$product['prod_description']}" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG['product_unit_price']}</td>
		<td><input type="text" name="prod_unit_price" size="25" value="{$product['prod_unit_price']}" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel['1']} <a href="./documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type="text" name="prod_custom_field1" size="50" value="{$product['prod_custom_field1']}" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel['2']} <a href="./documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type="text" name="prod_custom_field2" size="50" value="{$product['prod_custom_field2']}" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel['3']} <a href="./documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type="text" name="prod_custom_field3" size="50" value="{$product['prod_custom_field3']}" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$customFieldLabel['4']} <a href="./documentation/info_pages/custom_fields.html" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img></a></td>
		<td><input type="text" name="prod_custom_field4" size="50" value="{$product['prod_custom_field4']}" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG['notes']}</td>
		<td><textarea name="prod_notes" rows="8" cols="50">{$product['prod_notes']}</textarea></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG['product_enabled']}</td><td>{$display_block_enabled}</td>
	</tr>
	</table>

EOD;

$footer = <<<EOD
<hr></hr>
<input type="submit" name="cancel" value="{$LANG['cancel']}" />
<input type="submit" name="save_product" value="{$LANG['save_product']}" />
<input type="hidden" name="op" value="edit_product" />

EOD;
}


echo <<<EOD

<FORM name="frmpost" ACTION="index.php?module=products&view=save&submit={$_GET['submit']}" METHOD="POST" onsubmit="return frmpost_Validator(this)">
{$display_block}
{$footer}
EOD;
?>
</form>
<!-- ./src/include/design/footer.inc.php gets called here by controller srcipt -->
