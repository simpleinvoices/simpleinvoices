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

$bladeView -> assign("defaults",getSystemDefaults());
$product['attribute_decode'] = json_decode($product['attribute'],true);
$bladeView -> assign('product',$product);
$bladeView -> assign('taxes',$taxes);
$bladeView -> assign('tax_selected',$tax_selected);
$bladeView -> assign('customFieldLabel',$customFieldLabel);

$sql = "select * from ".TB_PREFIX."products_attributes";
$sth =  dbQuery($sql);
$attributes = $sth->fetchAll();
$bladeView -> assign("attributes", $attributes);

$bladeView -> assign('pageActive', 'product_manage');
$subPageActive = $_GET['action'] =="view"  ? "product_view" : "product_edit" ;
$bladeView -> assign('subPageActive', $subPageActive);
$bladeView -> assign('active_tab', '#product');
?>
