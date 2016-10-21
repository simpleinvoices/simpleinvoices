<?php
global $pdoDb, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// @formatter:off
$product_id = $_GET['id'];

$product = Product::select($product_id);

$customFieldLabel = getCustomFieldLabels('',true);
$taxes = Taxes::getActiveTaxes();
$tax_selected = Taxes::getTaxRate($product['default_tax_id']);

$product['attribute_decode'] = json_decode($product['attribute'],true);

$attributes = $pdoDb->request("SELECT", "products_attributes");

$subPageActive = $_GET['action'] =="view"  ? "product_view" : "product_edit" ;

$smarty->assign("defaults"        , getSystemDefaults());
$smarty->assign('product'         , $product);
$smarty->assign('taxes'           , $taxes);
$smarty->assign('tax_selected'    , $tax_selected);
$smarty->assign('customFieldLabel', $customFieldLabel);
$smarty->assign("attributes"      , $attributes);
$smarty->assign('pageActive'      , 'product_manage');
$smarty->assign('subPageActive'   , $subPageActive);
$smarty->assign('active_tab'      , '#product');
// @formatter:on
