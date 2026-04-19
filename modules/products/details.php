<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#get the invoice id
$product_id = (int)$_GET['id'];

$product = getProduct($product_id);
si_check_record_access($product);

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

$domain_id = domain_id::get();
$sql = "SELECT * FROM ".TB_PREFIX."products_attributes WHERE domain_id = :domain_id";
$sth = dbQuery($sql, ':domain_id', $domain_id);
$attributes = $sth->fetchAll();
$bladeView -> assign("attributes", $attributes);

$bladeView -> assign('pageActive', 'product_manage');
$subPageActive = $_GET['action'] =="view"  ? "product_view" : "product_edit" ;
$bladeView -> assign('subPageActive', $subPageActive);
$bladeView -> assign('active_tab', '#product');
?>
