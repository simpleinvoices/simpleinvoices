<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#get the invoice id
$product_id = $_GET['id'];

$product = getProduct($product_id);

#get custom field labels
$customFieldLabel = getCustomFieldLabels();
$taxes = getActiveTaxes();
$tax_selected = getTaxRate($product['default_tax_id']);

$smarty -> assign("defaults",getSystemDefaults());
$product['attribute_decode'] = json_decode($product['attribute'],true);
$smarty -> assign('product',$product);
$smarty -> assign('taxes',$taxes);
$smarty -> assign('tax_selected',$tax_selected);
$smarty -> assign('customFieldLabel',$customFieldLabel);

$sql = "select * from ".TB_PREFIX."products_attributes";
$sth =  $db->query($sql);
$attributes = $sth->fetchAll();
$smarty -> assign("attributes", $attributes);

$smarty -> assign('pageActive', 'product_manage');
$subPageActive = $_GET['action'] =="view"  ? "product_view" : "product_edit" ;
$smarty -> assign('subPageActive', $subPageActive);
$smarty -> assign('active_tab', '#product');
?>
