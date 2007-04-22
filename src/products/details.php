<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();


#table

//TODO: do check with php...
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

$smarty -> assign('product',$product);
$smarty -> assign('customFieldLabel',$customFieldLabel);

//TODO: Needed?
$smarty -> assign('wording_for_enabled',$wording_for_enabled);
$smarty -> assign('wording_for_disabled',$wording_for_disabled);


?>
