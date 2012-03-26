<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#get the invoice id
$product_id = $_GET['id'];

$SI_SYSTEM_DEFAULTS = new SimpleInvoices_Db_Table_SystemDefaults();
$SI_PRODUCTS = new SimpleInvoices_Db_Table_Products();
$SI_TAX = new SimpleInvoices_Db_Table_Tax();
$SI_CUSTOM_FIELDS = new SimpleInvoices_Db_Table_CustomFields();

$product = $SI_PRODUCTS->find($product_id);

#get custom field labels
$customFieldLabel = $SI_CUSTOM_FIELDS->getLabels();
$taxes = $SI_TAX->fetchAllActive();
$tax_selected = $SI_TAX->getTaxRateById($product['default_tax_id']);

$smarty -> assign("defaults",$SI_SYSTEM_DEFAULTS->fetchAll());
$smarty -> assign('product',$product);
$smarty -> assign('taxes',$taxes);
$smarty -> assign('tax_selected',$tax_selected);
$smarty -> assign('customFieldLabel',$customFieldLabel);

$smarty -> assign('pageActive', 'product_manage');
$subPageActive = $_GET['action'] =="view"  ? "product_view" : "product_edit" ;
$smarty -> assign('subPageActive', $subPageActive);
$smarty -> assign('active_tab', '#product');
?>