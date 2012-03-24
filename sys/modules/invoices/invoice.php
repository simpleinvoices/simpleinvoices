<?php
/*
* Script: invoice.php
* 	invoice page
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-19
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$SI_PRODUCTS = new SimpleInvoices_Products();
$SI_SYSTEM_DEFAULTS = new SimpleInvoices_SystemDefaults();

$billers = getActiveBillers();
$customers = getActiveCustomers();
$taxes = getActiveTaxes();
$products = $SI_PRODUCTS->findActive();
$preferences = getActivePreferences();
$defaults = $SI_SYSTEM_DEFAULTS->fetchAll();

if ($billers == null OR $customers == null OR $taxes == null OR $products == null OR $preferences == null)
{
    $first_run_wizard =true;
    $smarty -> assign("first_run_wizard",$first_run_wizard);
} else {
    $smarty -> assign("first_run_wizard",false);
}

$defaults['biller'] = (isset($_GET['biller'])) ? $_GET['biller'] : $defaults['biller'];
$defaults['customer'] = (isset($_GET['customer'])) ? $_GET['customer'] : $defaults['customer'];
$defaults['preference'] = (isset($_GET['preference'])) ? $_GET['preference'] : $defaults['preference'];
$defaultTax = getDefaultTax();
$defaultPreference = getDefaultPreference();

if (!empty( $_GET['line_items'] )) {
	$dynamic_line_items = $_GET['line_items'];
}
else {
	$dynamic_line_items = $defaults['line_items'] ;
}

for($i=1;$i<=4;$i++) {
	$show_custom_field[$i] = show_custom_field("invoice_cf$i",'',"write",'',"details_screen",'','','');
}

$smarty -> assign("billers",$billers);
$smarty -> assign("customers",$customers);
$smarty -> assign("taxes",$taxes);
$smarty -> assign("products",$products);
$smarty -> assign("preferences",$preferences);
$smarty -> assign("dynamic_line_items",$dynamic_line_items);
$smarty -> assign("show_custom_field",$show_custom_field);
$defaultCustomerID = (isset($defaultCustomerID['id']))? $defaultCustomerID['id']: $defaults['customer']['id'];
$smarty -> assign("defaultCustomerID", $defaultCustomerID);
$smarty -> assign("defaults",$defaults);

$smarty -> assign('active_tab', '#money');
?>