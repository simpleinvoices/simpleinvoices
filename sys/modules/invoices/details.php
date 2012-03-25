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

$SI_PRODUCTS = new SimpleInvoices_Db_Table_Products();
$SI_SYSTEM_DEFAULTS = new SimpleInvoices_Db_Table_SystemDefaults();
$SI_TAX = new SimpleInvoices_Db_Table_Tax();
$SI_CUSTOMERS = new SimpleInvoices_Db_Table_Customers();
$SI_BILLER = new SimpleInvoices_Db_Table_Biller();
$SI_PREFERENCES = new SimpleInvoices_Db_Table_Preferences();

#get the invoice id
$master_invoice_id = $_GET['id'];

$invoice = getInvoice($master_invoice_id);
$invoiceItems = invoice::getInvoiceItems($master_invoice_id);
//var_dump($invoiceItems);
$customers = $SI_CUSTOMERS->fetchAllActive();
$preference = getPreference($invoice['preference_id']);
$billers = $SI_BILLER->fetchAllActive();
//$taxes = $SI_TAX->fetchAllActive();
$defaults = $SI_SYSTEM_DEFAULTS->fetchAll();
$taxes = $SI_TAX->fetchAll();
$preferences = $SI_PREFERENCES->fetchAllActive();
$products = $SI_PRODUCTS->findActive();


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