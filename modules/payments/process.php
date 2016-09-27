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
    $invoiceobj = new Invoice();
    $invoice = $invoiceobj->select($_GET['id']);
} else {
    $pdoDb->addSimpleWhere("domain_id", domain_id::get());
    $invoice = $pdoDb->request("SELECT", "invoices");
}

// @formatter:off
$customer = Customer::get($invoice['customer_id']);
$biller   = Biller::select($invoice['biller_id']);
$defaults = getSystemDefaults();
//$pt       = PaymentType::select($defaults['payment_type']);
// @formatter:on

$invoices = new Invoice();
$invoices->sort='id';
$invoices->having='money_owed';
$invoices->having_and='real';
$invoice_all = $invoices->select_all('count');

$smarty->assign('invoice_all',$invoice_all);
$paymentTypes = PaymentType::select_all(true);

// @formatter:off
$smarty->assign("paymentTypes", $paymentTypes);
$smarty->assign("defaults"    , $defaults);
$smarty->assign("biller"      , $biller);
$smarty->assign("customer"    , $customer);
$smarty->assign("invoice"     , $invoice);
$smarty->assign("today"       , $today);

$subPageActive =  "payment_process" ;
$smarty->assign('pageActive'   , 'payment');
$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('active_tab'   , '#money');
// @formatter:on

