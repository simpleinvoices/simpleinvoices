<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$SI_PRODUCTS = new SimpleInvoices_Products();
$SI_SYSTEM_DEFAULTS = new SimpleInvoices_SystemDefaults();

$debtor = getTopDebtor();
$customer = getTopCustomer();
$biller = getTopBiller();

$billers = getBillers();
$customers = customer::get_all();
$taxes = getTaxes();
$products = $SI_PRODUCTS->fetchAll();
$preferences = getPreferences();
$defaults = $SI_SYSTEM_DEFAULTS->fetchAll();

if ($billers == null OR $customers == null OR $taxes == null OR $products == null OR $preferences == null)
{
    $first_run_wizard =true;
    $smarty -> assign("first_run_wizard",$first_run_wizard);
}

$smarty -> assign("mysql",$mysql);
$smarty -> assign("db_server",$db_server);
/*
$smarty -> assign("patch",count($patch));
$smarty -> assign("max_patches_applied", $max_patches_applied);
*/
$smarty -> assign("biller", $biller);
$smarty -> assign("billers", $billers);
$smarty -> assign("customer", $customer);
$smarty -> assign("customers", $customers);
$smarty -> assign("taxes", $taxes);
$smarty -> assign("products", $products);
$smarty -> assign("preferences", $preferences);
$smarty -> assign("debtor", $debtor);
//$smarty -> assign("title", $title);

$smarty -> assign('pageActive', 'dashboard');
$smarty -> assign('active_tab', '#home');
