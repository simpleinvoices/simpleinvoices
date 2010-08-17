<?php
/*
* Script: details.php
* 	invoice details page
*
* License:
*	 GPL v3 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
#table

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

#get the invoice id
$master_invoice_id = $_GET['id'];

$invoice = getInvoice($master_invoice_id);
$invoiceItems = invoice::getInvoiceItems($master_invoice_id);
//var_dump($invoiceItems);
$customers = getActiveCustomers();
$preference = getPreference($invoice['preference_id']);
$billers = getActiveBillers();
//$taxes = getActiveTaxes(); <--- look into this
$defaults = getSystemDefaults();
$taxes = getTaxes();
$preferences = getActivePreferences();
$products = getActiveProducts();


for($i=1;$i<=4;$i++) {
	$customFields[$i] = show_custom_field("invoice_cf$i",$invoice["custom_field$i"],"write",'',"details_screen",'','','');
}

$smarty -> assign("invoice",$invoice);
$smarty -> assign("defaults",$defaults);
$smarty -> assign("invoiceItems",$invoiceItems);
$smarty -> assign("customers",$customers);
$smarty -> assign("preference",$preference);
$smarty -> assign("billers",$billers);
$smarty -> assign("taxes",$taxes);
$smarty -> assign("preferences",$preferences);
$smarty -> assign("products",$products);
$smarty -> assign("customFields",$customFields);
$smarty -> assign("lines",count($invoiceItems));

$smarty -> assign('pageActive', 'invoice');
$smarty -> assign('subPageActive', 'invoice_edit');
$smarty -> assign('active_tab', '#money');
?>
