<?php
global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// @formatter:off
$billers     = Biller::get_all();
$customers   = Customer::get_all();
$taxes       = Taxes::getTaxes();
$products    = Product::select_all();
$preferences = Preferences::getPreferences();

if (empty($billers)   ||
    empty($customers) ||
    empty($taxes)     ||
    empty($products)  ||
    empty($preferences)) {
    $first_run_wizard =true;
    $smarty->assign("first_run_wizard",$first_run_wizard);
}

$smarty->assign("billers"    , $billers);
$smarty->assign("customers"  , $customers);
$smarty->assign("taxes"      , $taxes);
$smarty->assign("products"   , $products);
$smarty->assign("preferences", $preferences);
$smarty->assign('pageActive' , 'dashboard');
$smarty->assign('active_tab' , '#home');
// @formatter:on
