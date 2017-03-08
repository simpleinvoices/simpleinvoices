<?php
global $smarty;

// stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

// Add check_number field to the database if not present.
require_once "extensions/payments/include/class/CheckNumber.php";
CheckNumber::addNewFields();

// TODO - replace get..Payments with simple count - as data is got by xml.php now
// @formatter:off
$payments   = array();
$inv_id     = null;
$c_id       = null;
$preference = null;
$customer   = null;
// @formatter:on

if (!empty($_GET['id'])) {
    // Filter by just one invoice
    $inv_id        = $_GET['id'];
    $payments      = Payment::getInvoicePayments($_GET['id']);
    $invoice       = Invoice::getInvoice($_GET['id']);
    $preference    = Preferences::getPreference($invoice['preference_id']);
    $subPageActive = "payment_filter_invoice";
} else if (!empty($_GET['c_id'])) {
    // Filter by just one customer
    $c_id          = $_GET['c_id'];
    $payments      = Payment::getCustomerPayments($_GET['c_id']);
    $customer      = Customer::get($_GET['c_id']);
    $subPageActive = "payment_filter_customer";
} else {
    // No filters
    $payments = Payment::select_all();
    $subPageActive = "payment_manage";
}

$payments = Payment::progressPayments($payments); // payment type description to payments.

$smarty->assign("payments"  , $payments);
$smarty->assign("preference", $preference);
$smarty->assign("customer"  , $customer);

$smarty->assign("c_id"  , $c_id);
$smarty->assign("inv_id", $inv_id);

$smarty->assign('subPageActive', $subPageActive);
$smarty->assign('pageActive'   , 'payment');
$smarty->assign('active_tab'   , '#money');
