<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// @formatter:off
$billers     = getBillers();
$customers   = getCustomers();
$taxes       = getTaxes();
$products    = getProducts();
$preferences = getPreferences();
$defaults    = getSystemDefaults();

if (empty($billers)   ||
    empty($customers) ||
    empty($taxes)     ||
    empty($products)  ||
    empty($preferences)) {
    $first_run_wizard =true;
    $smarty->assign("first_run_wizard",$first_run_wizard);
}

$smarty->assign("mysql"      , $mysql);
$smarty->assign("db_server"  , $db_server);
$smarty->assign("biller"     , $biller);
$smarty->assign("billers"    , $billers);
$smarty->assign("customer"   , $customer);
$smarty->assign("customers"  , $customers);
$smarty->assign("taxes"      , $taxes);
$smarty->assign("products"   , $products);
$smarty->assign("preferences", $preferences);
$smarty->assign("debtor"     , $debtor);
$smarty->assign('pageActive' , 'dashboard');
$smarty->assign('active_tab' , '#home');
// @formatter:off
