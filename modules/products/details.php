<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#get the invoice id
$product_id = $_GET['submit'];

#customer query
$print_product = "SELECT * FROM {$tb_prefix}products WHERE id = $product_id";
$result_print_product = mysql_query($print_product, $conn) or die(mysql_error());

$product = mysql_fetch_array($result_print_product);

$product['wording_for_enabled'] = $product['enabled']==1?$LANG['enabled']:$LANG['disabled'];

#get custom field labels
$customFieldLabel = getCustomFieldLabels("product");

$smarty -> assign('product',$product);
$smarty -> assign('customFieldLabel',$customFieldLabel);

?>
