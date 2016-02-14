<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// @formatter:off
$product_id = $_GET['id'];

$product = getProduct($product_id);

$customFieldLabel = getCustomFieldLabels('',true);
$taxes = getActiveTaxes();
$tax_selected = getTaxRate($product['default_tax_id']);

$product['attribute_decode'] = json_decode($product['attribute'],true);

$sth = dbQuery("SELECT * FROM " . TB_PREFIX . "products_attributes");
$attributes = $sth->fetchAll();

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
?>
