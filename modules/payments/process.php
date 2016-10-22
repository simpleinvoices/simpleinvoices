<?php
global $smarty, $LANG, $pdoDb;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// Generate form validation script
jsBegin();
jsFormValidationBegin("frmpost");
jsValidateifNum("ac_amount",$LANG['amount']);
jsValidateifNum("ac_date",$LANG['date']);
jsFormValidationEnd();
jsEnd();
// end validation generation

$today = date("Y-m-d");

if(isset($_GET['id'])) {
    $invoice = Invoice::select($_GET['id']);
} else {
    $pdoDb->addSimpleWhere("domain_id", domain_id::get());
    $rows = $pdoDb->request("SELECT", "invoices");
    $invoice = $rows[0];
}

// @formatter:off
$customer = Customer::get($invoice['customer_id']);
$biller   = Biller::select($invoice['biller_id']);
$defaults = getSystemDefaults();

$pdoDb->setHavings(Invoice::buildHavings("money_owed"));
$invoice_all = Invoice::select_all("count", "id", "", null, "", "", "");

$smarty->assign('invoice_all',$invoice_all);
$paymentTypes = PaymentType::select_all(true);

$smarty->assign("paymentTypes", $paymentTypes);
$smarty->assign("defaults"    , $defaults);
$smarty->assign("biller"      , $biller);
$smarty->assign("customer"    , $customer);
$smarty->assign("invoice"     , $invoice);
$smarty->assign("today"       , $today);

$smarty->assign('pageActive'   , 'payment');
$smarty->assign('subPageActive', "payment_process");
$smarty->assign('active_tab'   , '#money');
// @formatter:on
