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


$billers = getActiveBillers();
$customers = getActiveCustomers();
$taxes = getTaxes();
$products = getActiveProducts();
$preferences = getActivePreferences();
$defaults = getSystemDefaults();

if ($billers == null OR $customers == null OR $taxes == null OR $products == null OR $preferences == null)
{
    $first_run_wizard =true;
}

$defaultBiller = getDefaultBiller();
$defaultCustomerID = (isset($_GET['customer_id'])) ? $_GET['customer_id'] : getDefaultCustomer();
$defaultTax = getDefaultTax();
$defaultPreference = getDefaultPreference();

if (!empty( $_GET['get_num_line_items'] )) {
	$dynamic_line_items = $_GET['get_num_line_items'];
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

$smarty -> assign("defaultCustomerID",$defaultCustomerID['id']);
$smarty -> assign("defaults",$defaults);

$smarty -> assign('active_tab', '#money');
$smarty -> assign("first_run_wizard",$first_run_wizard);

?>
