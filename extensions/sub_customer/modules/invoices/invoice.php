<?php
/*
 * Script: invoice.php
 *
 * Authors:
 *   Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 *   2016-07-27
 *
 * License:
 *   GPL v3 or above
 *
 * Website:
 *   http://www.simpleinvoices.org
 */
global $smarty;
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// @formatter:off
$billers     = getActiveBillers();
$customers   = getActiveCustomers();
$taxes       = getActiveTaxes();
$products    = getActiveProducts();
$preferences = getActivePreferences();
$defaults    = getSystemDefaults();

if ($billers == null || $customers == null ||
    $taxes   == null || $products  == null || $preferences == null) {
    $first_run_wizard =true;
    $smarty->assign("first_run_wizard",$first_run_wizard);
}

$defaults['biller']     = (isset($_GET['biller']))     ? $_GET['biller']     : $defaults['biller'];
$defaults['customer']   = (isset($_GET['customer']))   ? $_GET['customer']   : $defaults['customer'];
$defaults['preference'] = (isset($_GET['preference'])) ? $_GET['preference'] : $defaults['preference'];

$defaultTax        = getDefaultTax();
$defaultPreference = getDefaultPreference();
$defaultCustomer   = getDefaultCustomer();
$sub_customers     = SubCustomers::getSubCustomers($defaults['customer']);
if (!empty( $_GET['line_items'] )) {
    $dynamic_line_items = $_GET['line_items'];
} else {
    $dynamic_line_items = $defaults['line_items'] ;
}

$show_custom_field = array();
for($i=1;$i<=4;$i++) {
    $show_custom_field[$i] = show_custom_field("invoice_cf$i",'',"write",'',"details_screen",'','','');
}

$smarty->assign("billers"           ,$billers);
$smarty->assign("customers"         ,$customers);
$smarty->assign("sub_customers"     ,$sub_customers);
$smarty->assign("taxes"             ,$taxes);
$smarty->assign("defaultTax"        , $defaultTax);
$smarty->assign("products"          ,$products);
$smarty->assign("preferences"       ,$preferences);
$smarty->assign("defaultPreference" ,$defaultPreference);
$smarty->assign("dynamic_line_items",$dynamic_line_items);
$smarty->assign("show_custom_field" ,$show_custom_field);
$smarty->assign("defaultCustomerID" ,$defaultCustomer['id']);
$smarty->assign("defaults"          ,$defaults);
$smarty->assign('active_tab'        , '#money');
// @formatter:on
