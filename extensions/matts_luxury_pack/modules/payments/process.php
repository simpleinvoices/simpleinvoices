<?php
/*
 * Script: ./extensions/matts_luxury_pack/modules/payments/process.php
 * 	process payment page
 *
 * Authors:
 *	yumatechnical@gmail.com
 *
 * Last edited:
 * 	2016-08-29
 *
 * License:
 *	GPL v2 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$maxInvoice = maxInvoice();

// Generate form validation script
jsBegin();
jsFormValidationBegin("frmpost");
jsValidateifNum("ac_amount",$LANG['amount']);
jsValidateifNum("ac_date",$LANG['date']);
jsFormValidationEnd();
jsEnd();
// end validation generation

$today = date("Y-m-d");

$invoice = null;

if(isset($_GET['id'])) {
    $invoiceobj = new invoice();
    $invoice = $invoiceobj->select($_GET['id']);
} else {
    $sql = "SELECT * FROM ".TB_PREFIX."invoices WHERE domain_id = :domain_id";
    $sth = dbQuery($sql, ':domain_id', domain_id::get());
    $invoice = $sth->fetch(PDO::FETCH_ASSOC);
}

// @formatter:off
$customer = getCustomer($invoice['customer_id']);
$biller   = getBiller($invoice['biller_id']);
$defaults = getSystemDefaults();
$pt       = getPaymentType($defaults['payment_type']);
// @formatter:on

$invoices = new invoice();
$invoices->sort='id';
$invoices->having='money_owed';
$invoices->having_and='real';
$invoice_all = $invoices->select_all('count');

$smarty->assign('invoice_all',$invoice_all);
$paymentTypes = getActivePaymentTypes();

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

