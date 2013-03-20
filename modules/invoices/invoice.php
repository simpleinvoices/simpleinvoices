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
$taxes = getActiveTaxes();
$products = getActiveProducts();
$preferences = getActivePreferences();
$defaults = getSystemDefaults();

if ($billers == null OR $customers == null OR $taxes == null OR $products == null OR $preferences == null)
{
    $first_run_wizard =true;
    $smarty -> assign("first_run_wizard",$first_run_wizard);
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

$sql = "select CONCAT(a.id, '-', v.id) as id, CONCAT(a.name, '-',v.value) as display from ".TB_PREFIX."products_attributes a, ".TB_PREFIX."products_values v where a.id = v.attribute_id;";
$sth =  dbQuery($sql);
$matrix = $sth->fetchAll();
$smarty -> assign("matrix", $matrix);

$sql_prod = "select product_id as PID, (select count(product_id) from ".TB_PREFIX."products_matrix where product_id = PID ) as count from ".TB_PREFIX."products_matrix ORDER BY count desc LIMIT 1;";
$sth_prod =  dbQuery($sql_prod);
$number_of_products = $sth_prod->fetchAll();

$smarty -> assign("number_of_attributes", $number_of_products['0']['count']);
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

?>
